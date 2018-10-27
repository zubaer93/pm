<?php

namespace Api\Controller;

use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;

class UsersController extends AppController
{
    public function initialize()
    {
        $this->loadComponent('Paginator');
        parent::initialize();

    }

    /*get client id and client secret id for a logged in user
     *
     * @param  take user id from token
     * */
    public function token()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('ApiUsers'));
        if (!empty($this->jwtToken)) {
            $data['user_id'] = $this->jwtPayload->id;
            $data['client_id'] = $this->randomnum(16);
            $data['client_secret'] = md5($data['client_id']);
            $api_user = $this->ApiUsers->newEntity();
            $api_user = $this->ApiUsers->patchEntity($api_user, $data);
            if ($this->ApiUsers->save($api_user)) {
                $this->apiResponse['data'] = array('client_id' => $api_user['client_id'], 'client_secret' => $api_user['client_secret']);
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'You have to login first.';
        }
    }

    /* get logged in user info
     *
     *@param  from jwtPayload
     *
     * */
    public function me()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.AppUsers'));
        $user = $this->AppUsers->find()->where(['AppUsers.id' => $this->jwtPayload->id])->first();
        $user['bio'] = $user['description'];
        if (!empty($user)) {
            $this->apiResponse['data'] = $user;
        } else {
            throw new NotFoundException();
        }
    }

    /* update user info
     *
     *@param take user id from jwtPayload
     *
     * */
    public function edit()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.AppUsers'));
        $data = $this->request->getData();
        if (!empty($data)) {
            $user = $this->AppUsers->find()->where(['AppUsers.id' => $this->jwtPayload->id])->first();
            if (!empty($user)) {
                if(isset($data['experience'])) { $user->experince_id = $data['experience']; }  
                if(isset($data['investment_style'])) { $user->investment_style_id = $data['investment_style']; }
                if(isset($data['date_of_birth'])) { $user->date_of_birth = date("Y-m-d", strtotime($data['date_of_birth'])); }
                if(isset($data['bio'])) { $user->description = $data['bio']; }
                if(isset($data['zip_code'])) { $user->zip = $data['zip_code']; }
                $user = $this->AppUsers->patchEntity($user, $data);
                if ($this->AppUsers->save($user)) {
                    $this->apiResponse['message'] = 'User has been updated successfully.';
                } else {
                    $this->httpStatusCode = 403;
                    $this->apiResponse['error'] = 'User could not be updated. Please try again.';
                }
            } else {
                throw new NotFoundException();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'No data has found. Please enter user informations.';
        }
    }

    //get all user info
    public function userList()
    { 
        if(($this->jwtPayload->role) == 'admin')
        {
            $requestData = $this->request->getQuery();
            $this->request->allowMethod('get');
            $this->add_model(array('Api.AppUsers'));
            $users = $this->AppUsers->find('all');
    
            $detail = $users;
            $count = $users->count();
            $totalData = isset($count) ? $count : 0;
            $totalFiltered = $totalData;
    
            if (isset($requestData['search_value']) && !empty($requestData['search_value'])) {
                $search = $requestData['search_value'];
    
                $detail = $detail
                    ->where([
                        'OR' => [
                            ['(AppUsers.mobile_number) LIKE' => '%' . $search . '%'],
                            ['(AppUsers.first_name) LIKE' => '%' . $search . '%'],
                            ['(AppUsers.last_name) LIKE' => '%' . $search . '%'],
                        ]]);
    
                $company_count = $detail;
                $totalFiltered = $company_count->count();
            }
            $columns = array(
                0 => 'AppUsers.id',
                1 => 'AppUsers.mobile_number',
                2 => 'AppUsers.email',
                3 => 'AppUsers.first_name',
                4 => 'AppUsers.last_name',
                5 => 'AppUsers.activation_date',
                6 => 'AppUsers.active',
                7 => 'AppUsers.is_superuser',
                8 => 'AppUsers.role',
                9 => 'AppUsers.created',
                10 => 'AppUsers.modified',
                11 => 'AppUsers.avatar',
                12 => 'AppUsers.date_of_birth',
                13 => 'AppUsers.investment_style_id'
            );
            $order_col = isset($requestData['order_col']) ? $requestData['order_col'] : 0;
            $sidx = isset($columns[$order_col]) ? $columns[$order_col] : $columns[0];
            $sord = !isset($requestData['order_dir']) ? 'asc' : $requestData['order_dir'];
            $length = !isset($requestData['length']) ? 5 : $requestData['length'];
    
            $results = $this->paginate($detail
                ->select([
                    'id',
                    'mobile_number',
                    'email',
                    'first_name',
                    'last_name',
                    'name' => 'CONCAT(first_name," ",last_name)',
                    'active',
                    'activation_date',
                    'role',
                    'is_superuser',
                    'avatar',
                    'modified',
                    'created',
                    'date_of_birth',
                    'account_type',
                    'address1',
                    'nid_number',
                ])
                ->order($sidx . ' ' . $sord)
                ->limit((int)$length)
            );
            $paginate = $this->Paginator->configShallow(['data' => $results])->request->getParam('paging')['AppUsers'];
            $data = $results;
            $json_data = array(
                "data" => $data,
                "paginate" => $paginate
            );
            $this->apiResponse = $json_data;
        }
        else{
            $this->httpStatusCode = 500;
            $this->apiResponse['error'] = 'Authorization failed, only admin can access';
        }
      

    }

    /* update user info
     *
     *@param take username from query
     *
     * */
    public function getUser()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.AppUsers'));
        $this->loadModel('Api.Follow');
        $requestData = $this->request->getQuery();

        if (isset($requestData['username']) && !empty($requestData['username'])) {
            $user = $this->AppUsers->find()->where(['AppUsers.username' => $requestData['username']])->first();
            $isFollowing = $this->Follow->find()->where(['user_id' => $this->jwtPayload->id, 'follow_user_id' => $user['id']])->first();
            $user['following'] = false;
            if ($isFollowing) {
                $user['following'] = true;
            }
            if (!empty($user)) {
                $this->apiResponse = $user;
            } else {
                throw new NotFoundException();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'No data has found. Please enter username.';
        }
    }

    /* uploadVideo method Upload a vdo file and move to plugin/Api/upload/video/
     *
     * @param $id string user id
     * @return redirect to profile page
     */
    public function uploadDocuments()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.AppUsers'));
        if ($user = $this->AppUsers->get($this->jwtPayload->id)) {
            if ($data = $this->request->getData()) {
                if((int) $_SERVER['CONTENT_LENGTH'] > 10000000){
                    $this->httpStatusCode = 403;
                    $this->apiResponse['error'] = 'File size is large, upload maximum 2 mb files each';
                }
                else{
                    $data['id'] = $this->jwtPayload->id;
                    $result = $this->AppUsers->uploadDoc($data);
                    if ($result) {
                        if($result['nid']){ 
                            $this->apiResponse['nid'] = Router::url($result['nid'], true);
                        }
                        if($result['verifyPhoto']){ 
                            $this->apiResponse['verifyPhoto'] = Router::url($result['verifyPhoto'], true);
                        }
                        if($result['statement1']){ 
                            $this->apiResponse['statement'] = Router::url($result['statement1'], true);
                        }
                        if($result['utility_bill']){ 
                            $this->apiResponse['utility_bill'] = Router::url($result['utility_bill'], true);
                        }
                        if($result['others_doc']){ 
                            $this->apiResponse['others'] =  Router::url($result['others_doc'], true);
                        }
                        $this->apiResponse['message'] = 'Documents has been uploded successfully.';
                    } 
                    else {
                        $this->httpStatusCode = 403;
                        $this->apiResponse['error'] = 'Documents could not be uploaded. Please try again.';
                    }
                } 
            } else {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = 'No Documents found. Please upload an documents';
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please login to continue';
        }

    }

}
