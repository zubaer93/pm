<?php

namespace Api\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Api\Utility\JwtToken;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Routing\Router;
use Exception;
use League\OAuth2\Client\Token\AccessToken;

class AuthController extends AppController
{
    public function initialize()
    {
        $this->loadComponent('Paginator');
        parent::initialize();

    }

    /* login function
     *
     * @param $email, $password
     * */
    public function login()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.AppUsers', 'Api.Logs'));
        $data = $this->request->getData();
        if (!empty($data)) {
            try {
                if (!empty($data['email']) && !empty($data['password'])) {
                    $user = $this->AppUsers->find()->where(['email' => $data['email']])->orWhere(['username' => $data['email']])
                        ->first();
                    if ($user) {
                        if ($user['active'] == 1) {
                            if ((new DefaultPasswordHasher)->check($data['password'], $user['password'])) {
                                $this->UserLogin($user);
                            } else {
                                $this->httpStatusCode = 400;//
                                $this->apiResponse['error'] = 'Incorrect password.';
                            }
                        } else {
                            $this->httpStatusCode = 400;//
                            $this->apiResponse['error'] = 'Please activate your account';
                        }
                    } else {
                        $this->httpStatusCode = 400;//
                        $this->apiResponse['error'] = 'No such email or username found';
                    }
                } else {
                    $this->httpStatusCode = 400;// bad request
                    $this->apiResponse['error'] = 'Please enter email or username and password.';
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 400;// bad request
                $this->apiResponse['error'] = $e->getMessage();
            }
        } else {
            $this->httpStatusCode = 403; //forbidden , 404 : not found, 401 : unauthorized, 405 : method not allow (get/post)
            $this->apiResponse['error'] = 'Please enter email or username and password.';
        }
    }

    private function UserLogin($user)
    {
        $users = ['email' => $user['email'], 'id' => $user['id'], 'is_superuser' => $user['is_superuser'], 'role' => $user['role'], $date = date('Y-m-d h:i:s'), 'username' => $user['username']];
        if ($user['avatar']) {
            $avatar = $user['avatar'];
        } else {
            $avatar = Configure::read('Users.avatar.default');
        }
        $interval = date_diff($user->created, date_create());
     
        if($interval->days <=14){
            $isTrial = true;
        }
        else{
            $isTrial = false;
        }
        $user_data = array(
            'token' => JwtToken::generateToken($users),
            'user_id' => $user['id'],
            'name' => $user['first_name'] . ' ' . $user['last_name'],
            'email' => $user['email'],
            'avatar' => Router::url($avatar, true),
            'username' => $user['username'],
            'role' => $user['role'],
            'is_superuser' => $user['is_superuser'],
            'account_type' => $user['account_type'],
            'activation_date' => $user['activation_date'],
            'is_new' => $user->isNew(),
            'created_at' => $user->created,
            'isTrial' => $isTrial
        );
        // add logs
        //$options = array('token' => $user_data['token'], 'content_id' => $user['id'], 'user_id' => $user['id'], 'type' => 'users', 'action' => 'login');
        //$this->Logs->addToLog($options);
        $date = strtotime("+1 day");
        $this->AppUsers->patchEntity($user, ['token_expires' => date('Y-m-d 00:00:00', $date)]);
        $this->AppUsers->save($user);
        $this->apiResponse['data'] = $user_data;
    }

    public function register()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.AppUsers'));
        $this->AppUsers->getConnection()->begin();
        if (!empty($data = $this->request->getData())) {
            try {
                if (!empty($data['email']) && !empty($data['password'])) {
                    $check_email = $this->AppUsers->find()->where(['email' => $data['email']])->toArray();
                    if (count($check_email) == 0) {
                        $activation = $this->six_digit_random_number();
                        $data['token'] = $activation;
                        $data['active'] = 0;
                        $user = $this->AppUsers->newEntity();
                        $user->experince_id = $data['experience'];
                        $user->investment_style_id = $data['investment_style'];
                        $user->date_of_birth = date("Y-m-d", strtotime($data['birth_date']));
                        $user = $this->AppUsers->patchEntity($user, $data);
                        if ($this->AppUsers->save($user)) {
                            Configure::load('Api.appConfig', 'default');
                            $options = array('template' => 'register', 'to' => $user['email'], 'activation' => $activation, 'link' => Configure::read('activate_account'), 'subject' => 'Pocket Money Account Activation');
                            // send email for activation
                            $this->send_email($options);
                            $this->apiResponse['message'] = 'User has been saved successfully. An email is sent to your email address. Please check your email and activate account.';
                            $this->AppUsers->getConnection()->commit();
                        } else {
                            $this->httpStatusCode = 400;
                            if ($user->errors()) {
                                $this->apiResponse['error'] = '';
                                foreach ($user->errors() as $field => $validationMessage) {
                                    $this->apiResponse['error'] .= $validationMessage[key($validationMessage)] . ' | ';
                                }
                            }
                            $this->AppUsers->getConnection()->rollback();
                        }
                    } else {
                        $this->httpStatusCode = 400;// bad request
                        $this->apiResponse['error'] = 'This email is already registered';
                    }
                } else {
                    $this->httpStatusCode = 400;// bad request
                    $this->apiResponse['error'] = 'Please enter email and password.';
                    $this->AppUsers->getConnection()->rollback();
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = $e->getMessage();
                $this->AppUsers->getConnection()->rollback();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->AppUsers->getConnection()->rollback();
            $this->apiResponse['error'] = 'Please enter user informations';
        }
    }

    /** After completed registration,user accout will be activated, clicking email activation link
     * @ activation_code
     */
    public function activateUser()
    {
        $this->request->allowMethod('post');
        $this->loadModel('Api.AppUsers');
        $data = $this->request->getData();
        if (!empty($data)) {
            $check_user = $this->AppUsers->find()->where(['token' => $data['activation_code']])->first();
            if (!empty($check_user)) {
                $check_user->active = 1;
                $check_user->token = '';
                $check_user->activation_date = date('Y-m-d');
                if ($this->AppUsers->save($check_user)) {
                    $message = $this->confirmActivation($check_user['email']);
                    $this->apiResponse['message'] = 'Your account has been activated successfully.' . $message;
                } else {
                    $this->httpStatusCode = 400;
                    $this->apiResponse['error'] = 'Account could not be activated. Please try again later.';
                }
            } else {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'Invalid activation code.';
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please provide activation code.';
        }
    }

    public function confirmActivation($email)
    {
        if (!empty($email)) {
            $this->loadModel('Api.AppUsers');
            $user = $this->AppUsers->find()->where(['email' => $email])->first();
            $userName = "{$user['first_name']} {$user['last_name']}";
            $options = array('template' => 'welcome_user', 'to' => $email, 'user_details' => $userName, 'subject' => 'Welcome');
            $this->send_email($options);
            return $message = 'Account confirmation email has been sent successfully.';
        } else {
            return $message = 'Account confirmation email could not be sent.';
        }
    }

    public function forgetPassword()
    {
        $this->request->allowMethod('post');
        $this->loadModel('Api.AppUsers');
        if (!empty($data = $this->request->getData())) {
            if (!empty($data)) {
                $user = $this->AppUsers->find('all')->where(['email' => $data['email']])->first();
                if (empty($user)) {
                    $this->httpStatusCode = 404;
                    $this->apiResponse['error'] = 'This email is not existed.';

                } else {
                    $activation = $this->six_digit_random_number();//md5($this->randomnum(6));
                    $user->token = $activation;
                    //after one day activation code will expire
                    $date = strtotime("+1 day");
                    //$user['token_expires'] = date('Y-m-d', $date);
                    if ($this->AppUsers->save($user)) {
                        Configure::load('Api.appConfig', 'default');
                        $options = array('template' => 'forgot_password', 'to' => $data['email'], 'activation' => $activation, 'link' => Configure::read('reset_password'), 'subject' => 'Forgot Password');
                        $this->send_email($options);
                        $this->apiResponse['message'] = 'An email sent successfully. Please check your email.';

                    } else {
                        $this->httpStatusCode = 400;
                        $this->apiResponse['error'] = 'This email could not be sent. Please try again.';
                    }
                }
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please enter a email address';
        }
    }

    /* after clicking activation account link, user can reset his password
     *
     *@param  $activation_code, $password
     */
    public function resetPassword()
    {
        $this->request->allowMethod('post');
        $this->loadModel('Api.AppUsers');
        if (!empty($this->request->getData())) {
            $data = $this->request->getData();
            $check_user = $this->AppUsers->find()->where(['token' => $data['activation_code']])->first();
            if (empty($check_user)) {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'Invalid activation code';

            } else {
                $check_user['password'] = $data['password'];
                $check_user['token'] = '';
                if ($this->AppUsers->save($check_user)) {
                    $this->apiResponse['message'] = 'Password has been changed successfully.';
                } else {
                    $this->httpStatusCode = 400;
                    $this->apiResponse['error'] = 'Password could not be changed. Please try again.';
                }
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Invalid activation code';
        }
    }

    /* check email is existed in users table or not
      *@ input- email
      * @return message
      */
    public function checkEmail()
    {

        $this->request->allowMethod('post');
        $this->loadModel('Api.AppUsers');
        if (!empty($data = $this->request->getData())) {
            if (!empty($this->AppUsers->find()->where(['email' => $data['email'], 'api_token' => $this->jwtToken])->first())) {
                $this->apiResponse['message'] = 'ok';

            } elseif (!empty($this->AppUsers->find()->where(['email' => $data['email']])->first())) {

                $this->apiResponse['message'] = 'ok';
            } else {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = [];
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please enter a email address';
        }
    }

    /* check username is existed in users table or not
   *@ input- username
   * @return message
   */
    public function checkUsername()
    {

        $this->request->allowMethod('post');
        $this->loadModel('Api.AppUsers');
        if (!empty($data = $this->request->getData())) {
            if (!empty($this->AppUsers->find()->where(['username' => $data['user_name']])->first())) {
                $this->apiResponse['message'] = "ok";
            } else {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = [];
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please enter username';
        }
    }

    public function token()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.AppUsers'));
        $data = $this->request->getData();

        if (!empty($data['client_secret'])) {
            $user = $this->Users->get($this->jwtPayload->id);
            if (!empty($user)) {
                $api_user = $this->ApiUsers->find()->where(['client_id' => $data['client_id'], 'client_secret' => $data['client_secret']])->first();
                $jwt_key = ['client_id' => $data['client_id'], 'client_secret' => $data['client_secret']];
                if (!empty($api_user)) {
                    $api_token = JwtToken::generateToken($jwt_key);
                    $user = $this->Users->patchEntity($user, ['api_token' => $api_token]);
                    if ($this->Users->save($user)) {
                        $this->apiResponse['data'] = array('api_token' => $api_token);
                    }
                } else {
                    $this->httpStatusCode = 404;
                    $this->apiResponse['error'] = 'Invalid client id or client secret key.';
                }
            } else {
                throw new NotFoundException();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'You have to provide client id and client secret key.';
        }
    }

    /**
     * Change User password
     * if id is empty then current user's password will change, if id not empty, then password will change by * user id
     *
     * @param  token, $user_id, password
     */
    public function changePassword($id = null)
    {
        $this->request->allowMethod('post');
        $this->loadModel('Api.AppUsers');
        $data = $this->request->getData();
        if (!empty($data)) {
            try {
                if (empty($id)) {
                    $user = $this->AppUsers->get($this->jwtPayload->id);
                    if ((new DefaultPasswordHasher)->check($data['old_password'], $user['password'])) {
                        $user = $this->AppUsers->patchEntity($user, $data);
                        if ($this->AppUsers->save($user)) {
                            $this->apiResponse['message'] = 'Password has been changed successfully.';
                        } else {
                            $this->apiResponse['error'] = 'Password could not be changed. Please try again.';
                        }
                    } else {
                        $this->httpStatusCode = 403;
                        $this->apiResponse['error'] = 'Old password does not match. Please try again.';
                    }

                } else {
                    if (!empty($this->jwtPayload->role)) {
                        if ($this->jwtPayload->role == 'admin') {
                            $user = $this->Users->get($id);
                            $user = $this->Users->patchEntity($user, $data);
                            if ($this->Users->save($user)) {
                                $this->apiResponse['message'] = 'Password has been changed successfully.';
                            } else {
                                $this->apiResponse['error'] = 'Password could not be changed. Please try again.';
                            }
                        } else {
                            $this->httpStatusCode = 404;
                            $this->apiResponse = ['error' => 'You are not permitted to change this user password.'];
                        }
                    } else {
                        $this->httpStatusCode = 404;
                        $this->apiResponse = ['error' => 'You are not permitted to change this user password.'];
                    }
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse = ['message' => 'No request data has found. Please provide password'];
        }
    }

    /*Update the expiry time and generate a new token
     * */
    public function refreshToken()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.AppUsers', 'Api.Logs'));
        if (!empty($this->jwtToken)) {
            $user = $this->Users->find()->where(['api_token' => $this->jwtToken])->first();
            if (!empty($user)) {
                $users = ['email' => $user['email'], 'id' => $user['id'], 'is_superuser' => $user['is_superuser'], 'role' => $user['role'], $date = date('Y-m-d h:i:s'), 'username' => $user['username'],];
                $user_data = array(
                    'token' => JwtToken::generateToken($users),
                    'user_id' => $user['id'],
                    'name' => $user['first_name'] . ' ' . $user['last_name'],
                    'email' => $user['email'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'is_superuser' => $user['is_superuser']
                );
                $options = array('token' => $user_data['token'], 'content_id' => $user['id'], 'user_id' => $user['id'], 'type' => 'users', 'action' => 'login');
                $this->Logs->addToLog($options);
                $date = strtotime("+1 day");
                $this->Users->patchEntity($user, ['api_token' => $user_data['token'], 'token_expires' => date('Y-m-d 00:00:00', $date)]);
                $this->Users->save($user);
                $this->apiResponse['data'] = $user_data;
            } else {
                throw new NotFoundException();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please provide token.';
        }
    }

    /**
     * editAvatar method Upload a image file and move to /upload/avatar
     *
     * @param $id string user id
     * @return redirect to profile page
     */
    public function editAvatar()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.AppUsers'));
        if ($user = $this->AppUsers->get($this->jwtPayload->id)) {

            if ($data = $this->request->getData()) {
                $user = $this->AppUsers->patchEntity($user, $this->request->getData());
                if ($user->errors()) {
                    $this->Flash->error(__('Your avatar was not updated.'));
                    return $this->redirect(['_name' => 'settings']);
                }

                $data['id'] = $this->jwtPayload->id;
                $result = $this->AppUsers->uploadFile($data);
                if ($result) {
                    $this->apiResponse['data'] = Router::url($result['avatar'], true);
                    $this->apiResponse['message'] = 'Avatar has been updated successfully.';
                } else {
                    $this->apiResponse['error'] = 'Avatar could not be updated. Please try again.';
                }
            } else {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = 'No data found. Please upload an avatar';
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please login to continue';
        }

    }
      /**
     * uploadVideo method Upload a vdo file and move to plugin/Api/upload/video/
     *
     * @param $id string user id
     * @return redirect to profile page
     */
    public function uploadVideo()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.UserVideo', 'Api.AppUsers'));
        if ($user = $this->AppUsers->get($this->jwtPayload->id)) {
            $currentUser = $this->AppUsers->getUserAccountType($this->jwtPayload->id);
            if ($currentUser->account_type == 'PROFESSIONAL') {
                if ($data = $this->request->getData()) {
                    if((int) $_SERVER['CONTENT_LENGTH'] > 6000000){
                        $this->httpStatusCode = 403;
                        $this->apiResponse['error'] = 'Video size is larger than 5 MB';
                    }
                    else{
                        if( $data['type'] == 'personal'){
                            $checkDuplicate = $this->UserVideo->find()->
                                where(['video_title' => $data['video']['name'], 'user_id' => $this->jwtPayload->id])->first();
                        }
                        elseif( $data['type'] == 'youtube'){
                            $checkDuplicate = $this->UserVideo->find()->
                                where(['video_link' => $data['video'], 'user_id' => $this->jwtPayload->id])->first();
                        }
                        if(!$checkDuplicate){
                            $data['id'] = $this->jwtPayload->id;
                            $result = $this->UserVideo->uploadVideo($data);
                            if ($result) {
                                $this->apiResponse['data'] = Router::url($result['video_link'], true);
                                $this->apiResponse['message'] = 'Video has been uploded successfully.';
                            } 
                            else {
                                $this->httpStatusCode = 403;
                                $this->apiResponse['error'] = 'Video could not be uploaded. Please try again.';
                            }
                        }
                        else{
                            $this->httpStatusCode = 403;
                            $this->apiResponse['error'] = 'This video has already been uploaded';
                        }
                    } 
                } else {
                    $this->httpStatusCode = 403;
                    $this->apiResponse['error'] = 'No video found. Please upload an video';
                }
            }
            else{
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = 'Upgrade your account to PROFESSIONAL to upload video';
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please login to continue';
        }

    }

    public function getAvatar()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.AppUsers'));
        if ($user = $this->AppUsers->get($this->jwtPayload->id)) {
            if ($user['avatar']) {
                $this->apiResponse['data'] = Router::url($user['avatar'], true);
            } else {
                $this->apiResponse['error'] = 'No avatar found';
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please login to continue';
        }
    }

    public function getVideo()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.UserVideo'));
        if ($this->jwtPayload->id) {
            $videos = $this->UserVideo->find()
                ->where(['user_id' => $this->jwtPayload->id])
                //->groupBy('video_type')    
                ->toArray();
            if ($videos) {
                foreach($videos as $v){
                    $v['video_link'] = Router::url($v['video_link'], true);
                }
                $this->apiResponse['data'] = $videos;
            } else {
                $this->apiResponse['error'] = 'No videos found';
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please login to continue';
        }
    }

    /*
     * resend activation email. if admin resend activation code, he will provide user id. if user request for resend
     * activation code, he has to provide his email id
     *
     * @param $user_id, or email as form data
     * */
    public function resendEmailActivation($id = null)
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.AppUsers'));
        $data = $this->request->getData();
        if (!empty($id) || !empty($data['email'])) {
            $user = $this->AppUsers->find()->where(['id' => $id])->orWhere(['email' => $data['email']])->first();
            if (!empty($user)) {
                if ($user['active'] == 1) {
                    $this->httpStatusCode = 400;
                    $this->apiResponse['error'] = 'This account is already active';
                } else {
                    $activation = $this->six_digit_random_number();
                    $user->token = $activation;
                    if ($this->AppUsers->save($user)) {
                        Configure::load('Api.appConfig', 'default');
                        $options = array('template' => 'register', 'to' => $user['email'], 'activation' => $activation, 'link' => Configure::read('activate_account'), 'subject' => 'Stockgitter Account Activation');
                        $this->send_email($options);
                        $this->apiResponse['message'] = 'An email is sent to your email address. Please check your email and activate account.';

                    } else {
                        $this->httpStatusCode = 400;
                        $this->apiResponse['error'] = 'This email could not be sent. Please try again.';
                    }
                }
            } else {
                $this->httpStatusCode = 400;
                $this->apiResponse['error'] = 'Sorry. You have entered wrong user id or email.';
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please enter user id or email';
        }
    }


    /**
     * User logout
     */
    public function logout()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.AppUsers'));
        $data = $this->jwtToken;
        $user = $this->AppUsers->find()->where(['api_token' => $data])->first();
        if ($user) {
            $user->api_token = '';
            $this->AppUsers->save($user);
            $this->apiResponse['message'] = 'You have logged out successfully.';

            $this->apiResponse['message'] = 'You have logged out successfully.';
            return;
        }

    }

    /* login with facebook account
   *@param email, password
    * */
    public function socialLogin()
    {
        $token = $this->request->getQuery('access_token');
        $prov = $this->request->getQuery('provider');
        $access_token = new AccessToken(['access_token' => $token]);
        $provider = null;
        if ($prov == 'facebook') {
            $provider = new \League\OAuth2\Client\Provider\Facebook([
                'clientId' => '695659730774650',
                'clientSecret' => '404ff8a407e67cd465081599afe9131e',
                'graphApiVersion' => 'v2.10',
            ]);

        } elseif ($prov == 'google') {
            $provider = new \League\OAuth2\Client\Provider\Google([
                'clientId' => '204513801428-6tip4re8vbvp6hb0g26p3lh9hnf3376u.apps.googleusercontent.com',
                'clientSecret' => 'mBBCYyWFL_XDgi0X9PL02xBk',
                //'redirectUri'  => 'https://example.com/callback-url',
            ]);


        } elseif ($prov == null) {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'please insert a provider name';
            return;
        }

        try {
            // We got an access token, let's now get the owner details
            $ownerDetails = $provider->getResourceOwner($access_token);
            $id = $ownerDetails->getId();
            $name = $ownerDetails->getName();
            $firstName = $ownerDetails->getFirstName();
            $lastName = $ownerDetails->getLastName();
            $email = $ownerDetails->getEmail();
            $pictureUrl = $prov == 'google' ? $ownerDetails->getAvatar() : $ownerDetails->getPictureUrl();
            $gender = null;
            $minAge = null;

        } catch (\Exception $e) {
            // Failed to get user details
            $this->apiResponse['error'] = $e->getMessage();
            return;

        }
        $user_data = array(
            'id' => $id,
            'name' => $name,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'avatar' => $pictureUrl,
            'gender' => $gender,
            'minAge' => $minAge,
        );
        if (!empty($user_data)) {
            $this->loadModel('Api.AppUsers');
            $check_email = $this->AppUsers->find()->where(['email' => $user_data['email']])->first();
            if (!$check_email) {
                try {
                    $this->AppUsers->getConnection()->begin();
                    $role = "user";
                    $user_reg = array(
                        'avatar' => $user_data['avatar'],
                        'first_name' => $user_data['firstName'],
                        'last_name' => $user_data['lastName'],
                        'email' => $user_data['email'],
                        'username' => $user_data['firstName'] . strtotime(date('m/d/Y h:i:s a')),
                        'role' => $role,
                        'active' => 1,
                        'activation_date' => date('Y-m-d h:i:s'),
                        'password' => (new DefaultPasswordHasher)->hash('kdfg87dtsfkdgf7u&kugi7k'),
                        'is_superuser' => 0,
                    );
                    $userSave = $this->AppUsers->newEntity();
                    $userSave = $this->AppUsers->patchEntity($userSave, $user_reg);
                    if ($this->AppUsers->save($userSave)) {
                        $user = $this->AppUsers->find()->where(['email' => $userSave->email])->first();
                        $this->AppUsers->getConnection()->commit();
                        $this->UserLogin($user);
                    } else {
                        $this->AppUsers->getConnection()->rollback();
                        $this->httpStatusCode = 404;
                        $this->apiResponse['error'] = 'error';
                    }
                } catch (\Exception $e) {
                    $this->AppUsers->getConnection()->rollback();
                    $this->apiResponse['error'] = $e->getMessage();
                    $this->httpStatusCode = 500;
                }
            } else {
                $this->UserLogin($check_email);
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No data found';
        }
    }
}
