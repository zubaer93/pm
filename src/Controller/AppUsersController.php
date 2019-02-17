<?php

namespace App\Controller;

use CakeDC\Users\Controller\Component\UsersAuthComponent;
use Cake\Utility\Inflector;
use CakeDC\Users\Controller\UsersController;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use App\Model\Table\RatingsTable;
use CakeDC\Users\Controller\Traits\RegisterTrait;
use App\Model\Service\Core;
use Cake\Validation\Validator;

/**
 * Class AppUsersController
 * @package App\Controller
 */
class AppUsersController extends UsersController
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        if ($this->request->params['action'] == 'subscription') {
            $this->components()->unload('Csrf');
            $this->components()->unload('Security');
        }
    }

    /**
     * for solve afterregister problem
     */
    public function beforeFilter(\Cake\Event\Event $event)
    {
        if (empty($this->request->params['lang'])) {
            return $this->redirect(Configure::read('I18n.languages')[0] . $this->request->params['_matchedRoute']);
        }
    }

    /**
     * publicProfile method will return the public profile of an user.
     *
     * @param string $username User username
     * @return void
     */
    public function publicProfile($username)
    {
        $user = $this->AppUsers->getUserInfo($username);
        $rating = 0;

        if (!is_null($user['id'])) {
            $rating = $this->getUserRating($user['id']);
        }

        $this->set(compact('user', 'rating'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Login user
     *
     * @return mixed
     */
    public function login()
    {
        parent::login();
    }

    /**
     * Update remember me and determine redirect url after user identified
     * @param array $user user data after identified
     * @param bool $socialLogin is social login
     * @param bool $googleAuthenticatorLogin googleAuthenticatorLogin
     * @return array
     */
    protected function _afterIdentifyUser($user, $socialLogin = false, $googleAuthenticatorLogin = false)
    {
        if (!empty($user)) {
/**
 *            Temporary Fix for account type
 *
 **/
            if (!isset($user['account_type'])) {
                $user['account_type'] = 'FREE';
            }
            $this->Auth->setUser($user);
            $url = $this->Auth->redirectUrl();
            if ($user['enable']) {
                $this->request->session()->destroy();
                $this->Flash->error(__d('CakeDC/Users', 'Your page is blocked.'));
                return $this->redirect($this->Auth->logout());
            }
            if ($googleAuthenticatorLogin) {
                $url = Configure::read('GoogleAuthenticator.verifyAction');
            }

            if ($this->_isAdmin()) {
                return $this->redirect(['_name' => 'admin_home']);
            }
            if (is_null($user['experince_id']) || is_null($user['investment_style_id'])) {
                $this->Flash->set(__('You must fill out all the information about you.'), ['key' => 'no_information']);

                return $this->redirect(['_name' => 'settings']);
            }
            $event = $this->dispatchEvent(UsersAuthComponent::EVENT_AFTER_LOGIN, ['user' => $user]);

            if ($user['account_type'] == 'FREE' && $socialLogin) {
                return $this->redirect(['_name' => 'describe']);
            }

            if (is_array($event->result)) {
                return $this->redirect($event->result);
            }

            if ($this->referer() != '/') {
                return $this->redirect($this->referer());
            }

            if ($this->request->session()->read('event_redirect')) {
                $url = $this->request->session()->consume('event_redirect');
            }

            return $this->redirect($url);
        } else {
            if (!$socialLogin) {
                $requestData = $this->request->getData();
                $users = $this->AppUsers->find()
                    ->where(['username' => $requestData['username']])
                    ->orWhere(['email' => $requestData['username']])
                    ->toArray();
                $bool = false;
                foreach ($users as $user) {
                    if (password_verify($requestData['password'], $user->password)) {
                        $bool = true;
                        break;
                    }
                }
                if ($bool) {
                    $message = __d('CakeDC/Users', 'Please validate your account before log in');
                    $this->Flash->error($message, 'default', [], 'auth');
                } else {
                    $message = __d('CakeDC/Users', 'Username or password is incorrect');
                    $this->Flash->error($message, 'default', [], 'auth');
                }
            }
            return $this->redirect(Configure::read('Auth.loginAction'));
        }
    }

    /**
     * register method Override register user method
     *
     * @return mixed
     */
    public function register()
    {
        parent::register();
        $this->loadModel('Pages');
        $page = $this->Pages->getPages(__('terms-of-service'));

        $this->set(compact('page'));
    }

    /**
     * Prepare flash messages after registration, and dispatch afterRegister event
     *
     * @param EntityInterface $userSaved User entity saved
     * @return Response
     */
    protected function _afterRegister(\Cake\Datasource\EntityInterface $userSaved)
    {
        $validateEmail = (bool)Configure::read('Users.Email.validate');
        $message = __d('CakeDC/Users', 'You have registered successfully, please log in');
        if ($validateEmail) {
            $message = __d('CakeDC/Users', 'Please validate your account before log in');
        }
        $event = $this->dispatchEvent(UsersAuthComponent::EVENT_AFTER_REGISTER, [
            'user' => $userSaved
        ]);

        if ($event->result instanceof Response) {
            return $event->result;
        }
        $this->Flash->success($message);

        return $this->redirect(['_name' => 'subscribe']);
    }

    /**
     * edit method Override edit user method Updates too the description of social accounts
     *
     * @param $id string user id
     * @return mixed
     */
    public function editSimulation($id = null)
    {
        $table = $this->loadModel('SimulationSetting');
        $tableAlias = $table->alias();

        $entity = $table->find()->where(['user_id' => $id])
            ->first();
        if ($entity) {
            $this->set($tableAlias, $entity);
            $this->set('tableAlias', $tableAlias);
            $this->set('_serialize', [$tableAlias, 'tableAlias']);
            if (!$this->request->is(['patch', 'post', 'put'])) {
                return;
            }
            $entity = $table->patchEntity($entity, $this->request->getData());
            if ($table->save($entity)) {
                $this->Flash->success('The simulation has been saved');

                return $this->redirect(['_name' => 'simulation']);
            }
        } else {
            $result = $table->setSimulation($this->request->getData(), $id);
            if ($result) {
                $this->Flash->success('The simulation has been saved');

                return $this->redirect(['_name' => 'simulation']);
            }
        }

        $this->Flash->error('The simulation could not be saved');
    }

    /**
     * edit method Override edit user method Updates too the description of social accounts
     *
     * @param $id string user id
     * @return mixed
     */
    public function editPost($id = null)
    {
        $table = $this->loadModel();
        $tableAlias = $table->alias();
        $entity = $table->get($id, [
            'contain' => []
        ]);
        $this->set($tableAlias, $entity);
        $this->set('tableAlias', $tableAlias);
        $this->set('_serialize', [$tableAlias, 'tableAlias']);
        if (!$this->request->is(['patch', 'post', 'put'])) {
            return;
        }
        $entity = $table->patchEntity($entity, $this->request->getData());

        if ($table->save($entity)) {
            $this->Flash->success(__d('CakeDC/Users', 'The {0} has been saved', 'user'));

            return $this->redirect(['_name' => 'settings']);
        }
        $this->Flash->error(__d('CakeDC/Users', 'The {0} could not be saved', 'user'));
    }

    /**
     * editAvatar method Upload a image file and move to /upload/avatar
     *
     * @param $id string user id
     * @return redirect to profile page
     */
    public function editAvatar($id)
    {
        $user = $this->AppUsers->get($id);

        if ($this->request->is('put')) {

            $user = $this->AppUsers->patchEntity($user, $this->request->getData());

            if ($user->errors()) {
                $this->Flash->error(__('Your avatar was not updated.'));
                return $this->redirect(['_name' => 'settings']);
            }

            $result = $this->AppUsers->uploadFile($this->request->getData());

            if ($result) {
                $this->Flash->success(__('Your profile has been sucessfully updated.'));
            } else {
                $this->Flash->error(__('Your avatar was not updated.'));
            }

            return $this->redirect(['_name' => 'settings']);
        }
    }

    /**
     * profile method will redirect to profile
     *
     * @return void
     */
    public function comment()
    {
        if (!$this->Auth->user('id')) {
            $this->redirect(['_name' => 'login']);
        }
        parent::profile();
        $Users = TableRegistry::get('Users');
        $all_users = $Users->find('list', array('fields' => array('username', 'id')))
            ->toArray();
    }

    /**
     * profile method will redirect to profile
     *
     * @return void
     */
    public function profile($id = null)
    {
        if (!$this->Auth->user('id')) {
            $this->redirect(['_name' => 'login']);
        }

        parent::profile();

        $userId = $this->Auth->user('id');
        $currentLanguage = $this->_getCurrentLanguageId();

        $this->loadModel('Follow');
        $followers = $this->Follow->find('all')
            ->contain('Follower')
            ->where(['Follow.follow_user_id' => $userId])
            ->order(['Follow.created_at' => 'desc'])
            ->toArray();

        $following = $this->Follow->find('all')
            ->contain('Following')
            ->where(['Follow.user_id' => $userId])
            ->order(['Follow.created_at' => 'desc'])
            ->toArray();

        if ($this->Auth->user()) {
            $avatarPath = $this->AppUsers->get($this->Auth->user('id'))->avatarPath;
            if ($avatarPath == Configure::read('Users.Avatar.placeholder')) {
                $avatarPath = Configure::read('Users.avatar.default');
            }
        } else {
            $avatarPath = '';
        }

        $this->loadModel('Countries');
        $countryName = $this->Countries->getName($currentLanguage);

        $rating = 0;

        if (!is_null($userId)) {
            $rating = Core::getUserRating($userId);
        }
        ///this is for ajax///

        $page_is = 'user';
        $page_data = $userId;

        //////////////////////

        $this->loadModel('WatchlistGroup');
        $stockWatchlists = $this->WatchlistGroup->getWatchlists($this->Auth->user('id'));

        $this->set(compact('followers', 'following', 'countryName', 'userId', 'currentLanguage', 'avatarPath', 'rating', 'page_is', 'page_data', 'stockWatchlists'));
        $this->set('_serialize', ['messages']);
    }

    /**
     * settings method will redirect to profile
     *
     * @return void
     */
    public function settings()
    {
        if (!$this->Auth->user('id')) {
            $this->redirect(['_name' => 'login']);
        }

        $this->loadModel('WatchlistGroup');
        $stockWatchlists = $this->WatchlistGroup->getWatchlists($this->Auth->user('id'));

        $this->set(compact('stockWatchlists'));
        parent::profile();
    }

    /**
     * settings method will redirect to profile
     *
     * @return void
     */
    public function alerts()
    {
        if (!$this->Auth->user('id')) {
            $this->redirect(['_name' => 'login']);
        }
        //dd($this->request->getData());
        if ($this->request->is(['post', 'put'])) {
            if ($this->AppUsers->saveAlerts($this->request->getData(), $this->Auth->user('id'))) {
                $this->Flash->success(__('Alerts were saved successfully'));
            } else {
                $this->Flash->error(__('Alerts were not saved successfully'));
            }
        }

        $user = $this->AppUsers->get($this->Auth->user('id'), [
            'contain' => ['EmailAlerts', 'SmsAlerts', 'TimeAlerts']
        ]);

        $globalNotifications = $this->AppUsers->EmailAlerts->GlobalAlerts->find();

        $this->set(compact('user', 'globalNotifications'));
    }

    /**
     *
     */
    public function simulation()
    {
        if (!$this->Auth->user('id')) {
            $this->redirect(['_name' => 'login']);
        }
        parent::profile();
        $Brokers = TableRegistry::get('Brokers');
        $result = $Brokers->find()
            ->where(['Brokers.enable' => 0])
            ->toArray();

        $all_brokers = [];
        foreach ($result as $val) {
            $all_brokers[$val['id']] = $val['first_name'] . ' ' . $val['last_name'];
        }

        $this->loadModel('SimulationSetting');
        $simulation = $this->SimulationSetting->find('all')
            ->contain('Brokers')
            ->where(['user_id' => $this->Auth->user('id')])
            ->first();

        $this->loadModel('WatchlistGroup');
        $stockWatchlists = $this->WatchlistGroup->getWatchlists($this->Auth->user('id'));

        $this->set(compact('all_brokers', 'simulation', 'stockWatchlists'));
    }

    /**
     * stocks method will set the stock for the profile
     *
     * @param string $stocks
     * @return void
     */
    public function stock($stock = null)
    {
        $this->set('stock', $stock);
        parent::profile();
    }

    /**
     * Gets the users to use at mention.js
     *
     * @return void
     */
    public function getMentionUsers()
    {
        $users = $this->AppUsers->getMentionUsers()->toArray();
        $mentionUsers = [];
        foreach ($users as $key => $user) {
            $mentionUsers[] = [
                'name' => $user->full_name,
                'username' => $user->username,
                'picture' => $user->avatar,
            ];
        }

        $mentionUsers = array_values($mentionUsers);

        $this->set(compact('mentionUsers'));
        $this->set('_serialize', ['mentionUsers']);
    }

    /**
     * requestResetPassword method will redirect to reset password
     *
     * @return void
     */
    public function requestResetPassword()
    {
        $this->set('user', $this->getUsersTable()->newEntity());
        $this->set('_serialize', ['user']);
        if (!$this->request->is('post')) {
            return;
        }
        $reference = $this->request->getData('reference');
        try {

            $resetUser = $this->getUsersTable()->resetToken($reference, [
                'expiration' => Configure::read('Users.Token.expiration'),
                'checkActive' => false,
                'sendEmail' => true,
                'ensureActive' => Configure::read('Users.Registration.ensureActive')
            ]);
            if ($resetUser) {
                $msg = __d('CakeDC/Users', 'Please check your email to continue with password reset process');
                $this->Flash->success($msg);
            } else {
                $msg = __d('CakeDC/Users', 'The password token could not be generated. Please try again');
                $this->Flash->error($msg);
            }

            return $this->redirect(['_name' => 'login']);
        } catch (\CakeDC\Users\Exception\UserNotFoundException $exception) {
            $this->Flash->error(__d('CakeDC/Users', 'User {0} was not found', $reference));
        } catch (\CakeDC\Users\Exception\UserNotActiveException $exception) {
            $this->Flash->error(__d('CakeDC/Users', 'The user is not active'));
        } catch (\Cake\Core\Exception\Exception $exception) {
            $this->Flash->error(__d('CakeDC/Users', 'Token could not be reset'));
            $this->log($exception->getMessage());
        }
    }

    /**
     * Reset password
     *
     * @param null $token token data.
     * @return void
     */
    public function resetPassword($token = null)
    {
        $this->validate('password', $token);
    }

    /**
     * activation
     *
     * @param null $token token data.
     * @return void
     */
    public function activation($token = null)
    {
        $this->validate('email', $token);
    }

    /**
     * Validates email
     *
     * @param string $type 'email' or 'password' to validate the user
     * @param string $token token
     * @return Response
     */
    public function validate($type = 'email', $token = null)
    {
        try {
            switch ($type) {
                case 'email':
                    try {
                        $result = $this->getUsersTable()->validate($token, 'activateUser');
                        if ($result) {
                            $this->Flash->success(__d('CakeDC/Users', 'User account validated successfully'));
                            $this->AppUsers->welcomeEmail($result);
                        } else {
                            $this->Flash->error(__d('CakeDC/Users', 'User account could not be validated'));
                        }
                    } catch (\CakeDC\Users\Exception\UserAlreadyActiveException $exception) {
                        $this->Flash->error(__d('CakeDC/Users', 'User already active'));
                    }
                    break;
                case 'password':
                    $result = $this->getUsersTable()->validate($token);
                    if (!empty($result)) {
                        $this->Flash->success(__d('CakeDC/Users', 'Reset password token was validated successfully'));
                        $this->request->session()->write(
                            Configure::read('Users.Key.Session.resetPasswordUserId'), $result->id
                        );

                        return $this->redirect(['action' => 'changePassword']);
                    } else {
                        $this->Flash->error(__d('CakeDC/Users', 'Reset password token could not be validated'));
                    }
                    break;
                default:
                    $this->Flash->error(__d('CakeDC/Users', 'Invalid validation type'));
            }
        } catch (\CakeDC\Users\Exception\UserNotFoundException $ex) {
            $this->Flash->error(__d('CakeDC/Users', 'Invalid token or user account already validated'));
        } catch (\CakeDC\Users\Exception\TokenExpiredException $ex) {
            $this->Flash->error(__d('CakeDC/Users', 'Token already expired'));
        }

        return $this->redirect(['_name' => 'login']);
    }

    /**
     * getUserRating method will redirect to user rating
     * @param string $user_id
     * @return integer
     */
    public function getUserRating($user_id)
    {
        $rating = 0;

        if (!is_null($user_id)) {
            $this->loadModel('Messages');
            $messages = $this->Messages->find('all')
                ->where(['user_id' => $user_id])
                ->order(['Messages.modified DESC'])
                ->contain(['AppUsers'])
                ->toArray();
            $count = 0;
            $averageStatistically = 0;
            foreach ($messages as $message) {
                $averageStatistically += RatingsTable::getAverageRanking($message['id']);
                $count++;
            }
            if ($count) {
                $rating = (int)($averageStatistically / $count);
            }
        }

        return $rating;
    }

    /**
     * getUserRating method will redirect to user rating
     * @param string $user_id
     * @return integer
     */
    public function follow()
    {
        $requestData = $this->request->getQuery('username');
        $userDetails = $this->AppUsers->getUserInfo($requestData);
        if (!is_null($userDetails)) {
            $this->loadModel('Follow');
            $result = $this->Follow->setOrUpdate($userDetails['id'], $this->Auth->user('id'));
            if ($result[0]) {
                $response = [
                    'status' => 'success',
                    'message' => 'Successful!',
                    'data' => $result[1]
                ];
                $this->response->statusCode(200);
                $this->setJsonResponse($response);
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error!'
                ];
                $this->response->statusCode(500);
                $this->setJsonResponse($response);
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error!'
            ];
            $this->response->statusCode(500);
            $this->setJsonResponse($response);
        }
        return $this->response;
    }

    protected function setJsonResponse($response)
    {
        $response = json_encode($response);
        $this->response->type('application/json');
        $this->response->body($response);
    }

    /**
     * Change password
     *
     * @return mixed
     */
    public function changePassword()
    {
        $user = $this->getUsersTable()->newEntity();
        $id = $this->Auth->user('id');
        if (empty($id)) {
            return $this->redirect(['action' => 'login']);
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            try {
                $validator = $this->getUsersTable()->validationPasswordConfirm(new Validator());
                if (!empty($id)) {
                    $validator = $this->getUsersTable()->validationCurrentPassword($validator);
                }
                $user->id = $id;
                $user = $this->getUsersTable()->patchEntity(
                    $user,
                    $this->request->getData(),
                    ['validate' => $validator]
                );

                if ($user->getErrors()) {
                    $this->Flash->error(__d('CakeDC/Users', 'Password could not be changed'));
                } else {
                    $user = $this->getUsersTable()->changePassword($user);
                    if ($user) {
                        $this->Flash->success(__d('CakeDC/Users', 'Password has been changed successfully'));
                    } else {
                        $this->Flash->error(__d('CakeDC/Users', 'Password could not be changed'));
                    }
                }
                $this->set(compact('user'));
                $this->set('_serialize', ['user']);
                return $this->redirect(['action' => 'settings']);
            } catch (UserNotFoundException $exception) {
                $this->Flash->error(__d('CakeDC/Users', 'User was not found'));
            } catch (WrongPasswordException $wpe) {
                $this->Flash->error($wpe->getMessage());
            } catch (Exception $exception) {
                $this->Flash->error(__d('CakeDC/Users', 'Password could not be changed'));
                $this->log($exception->getMessage());
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);

    }

    /**
     * subscribe method it will show all plans available
     *
     * @param string $plan Subscription Plan
     * @return void
     */
    public function subscribe()
    {
        $plans = $this->AppUsers->enum('account_type');
        $this->set(compact('plans'));
    }

    /**
     * subscription method it will show all plans available
     *
     * @param string $accountType Subscription Plan
     * @return void
     */
    public function subscription()
    {
        if (!$this->request->is('post')) {
            return $this->redirect(['_name' => 'home']);
        }

        $accountType = $this->request->getData('item_name');

        $accountTypes = $this->AppUsers->enum('account_type');
        if (!empty($accountTypes[$accountType])) {
            $user = $this->AppUsers->get($this->Auth->user('id'));
            $user->account_type = $accountType;
            if ($this->AppUsers->save($user)) {
                $this->Auth->setUser($user);
                $this->Flash->success(__('Your plan was updated successfully'));
            } else {
                $this->Flash->error(__('Your plan was not updated successfully'));
            }
        } else {
            $this->Flash->error(__('Your plan was not updated successfully'));
        }

        $this->redirect(['action' => 'subscribe']);
    }

}
