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
                        ['(AppUsers.username) LIKE' => '%' . $search . '%'],
                        ['(AppUsers.first_name) LIKE' => '%' . $search . '%'],
                        ['(AppUsers.last_name) LIKE' => '%' . $search . '%'],
                    ]]);

            $company_count = $detail;
            $totalFiltered = $company_count->count();
        }
        $columns = array(
            0 => 'AppUsers.id',
            1 => 'AppUsers.username',
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
                'username',
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
                'investment_style_id'
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

    /**
     * settings method will redirect to profile
     *
     * @return void
     */
    public function alerts()
    {
        $this->add_model(array('Api.AppUsers'));
        if ($this->jwtPayload->id) {
            if ($this->request->is(['post', 'put'])) {
                if ($this->AppUsers->saveAlerts($this->request->getData(), $this->jwtPayload->id)) {
                    $this->apiResponse['message'] = 'Alerts were saved successfully';
                } else {
                    $this->httpStatusCode = 500;
                    $this->apiResponse['error'] = 'Alert Can\'t be save';
                }
                return;
            }
            $user = $this->AppUsers->get($this->jwtPayload->id, [
                'contain' => ['EmailAlerts', 'SmsAlerts', 'TimeAlerts']
            ]);

            $data = $this->alertData($user);

            if (!empty($data)) {
                $this->apiResponse['data'] = $data;
            } else {
                $this->apiResponse['error'] = 'error';
            }

        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'not logged in';
        }
    }

    private function alertData($user)
    {
        $email = [];
        $sms = [];
        foreach ($user['email_alerts'] as $users) {
            $email[] = $users['global_alert_id'];
        }
        foreach ($user['sms_alerts'] as $usersms) {
            $sms[] = $usersms['global_alert_id'];
        }

        $email_watchlist = 0;
        $email_stock = 0;
        $email_event = 0;
        $sms_watchlist = 0;
        $sms_stock = 0;
        $sms_event = 0;

        if (in_array("1", $email)) {
            $email_watchlist = 1;
        }
        if (in_array("2", $email)) {
            $email_stock = 1;
        }
        if (in_array("3", $email)) {
            $email_event = 1;
        }
        if (in_array("1", $sms)) {
            $sms_watchlist = 1;
        }
        if (in_array("2", $sms)) {
            $sms_stock = 1;
        }
        if (in_array("3", $sms)) {
            $sms_event = 1;
        }
        $data['time_alerts'] = $user['time_alerts'];
        $data['email_watchlist'] = $email_watchlist;
        $data['email_stock'] = $email_stock;
        $data['email_event'] = $email_event;
        $data['sms_watchlist'] = $sms_watchlist;
        $data['sms_stock'] = $sms_stock;
        $data['sms_event'] = $sms_event;

        return $data;
    }

}
