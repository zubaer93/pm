<?php

namespace Api\Controller;

use Cake\Network\Exception\NotFoundException;

class PageController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');

    }

    /* this function will return about-us page content
     * */
    public function about()
    {
        $this->loadModel('Pages');
        $page = $this->Pages->find('all')
            ->order(['position ASC'])
            ->where(['slug' => 'about-us'])->first();
       if(!empty($page)){
           $this->apiResponse['data'] = $page;
       }
       else {
           throw new NotFoundException();
       }
    }

    /* this function will return term of service page content
     * */
    public function termOfService()
    {
        $this->loadModel('Pages');
        $term_of_service = $this->Pages->find('all')
            ->order(['position ASC'])
            ->where(['slug' => 'terms-of-service'])->first();
        if(!empty($term_of_service)){
            $this->apiResponse['data'] = $term_of_service;
        }
        else {
            throw new NotFoundException();
        }
    }
    public function prequalification()
    {
        $checked = 0;
        $bool = true;
        $first_name = '';

        if ($this->request->is('post') && !is_null($this->request->getData())) {
            $timeForDatabase = (new \Cake\I18n\Time(\Cake\I18n\Time::now(), 'America/New_York'))->setTimezone('US/Eastern');
            $day = date("Y-m-d", strtotime($timeForDatabase));
            $requestData = $this->request->getData();

            switch ($requestData['country_currently']) {
                case 1:
                    {

                        if (!strlen($requestData['first_name'])) {
                            $bool = false;
                        }
                        if (!strlen($requestData['last_name'])) {
                            $bool = false;
                        }

                        if (!strlen($requestData['age']) || $requestData['age'] < 18) {
                            $bool = false;
                        }
//                        if (!strlen($requestData['social_security_number']) && !strlen($requestData['tax_id_number'])) {
//                            $bool = false;
//                        }
                        if (!strlen($requestData['passport_exp']) || $day > $requestData['passport_exp']) {
                            $bool = false;
                        }

                        if (!strlen($requestData['visa_exp_date']) || $day > $requestData['visa_exp_date']) {
                            $bool = false;
                        }

                        break;
                    }
                case 2:
                    {
                        if (!strlen($requestData['first_name'])) {
                            $bool = false;
                        }

                        if (!strlen($requestData['last_name'])) {
                            $bool = false;
                        }

//                        if (!strlen($requestData['social_security_number']) && !strlen($requestData['tax_id_number'])) {
//                            $bool = false;
//                        }
                        if (!strlen($requestData['age']) || $requestData['age'] < 18) {
                            $bool = false;
                        }

                        if (!strlen($requestData['passport_exp']) || $day > $requestData['passport_exp']) {
                            $bool = false;
                        }

                        if (!strlen($requestData['visa_exp_date']) || $day > $requestData['visa_exp_date']) {
                            $bool = false;
                        }

                        break;
                    }
                case 3:
                    {
                        if (!strlen($requestData['first_name'])) {
                            $bool = false;
                        }
                        if (!strlen($requestData['last_name'])) {
                            $bool = false;
                        }

                        if (!strlen($requestData['age']) || $requestData['age'] < 18) {
                            $bool = false;
                        }

                        if (!strlen($requestData['passport_exp']) || $day > $requestData['passport_exp']) {
                            $bool = false;
                        }

                        if (!strlen($requestData['visa_exp_date']) || $day > $requestData['visa_exp_date']) {
                            $bool = false;
                        }

                        break;
                    }
                case 4:
                    {
                        if (!strlen($requestData['first_name'])) {
                            $bool = false;
                        }
                        if (!strlen($requestData['last_name'])) {
                            $bool = false;
                        }

                        if (!strlen($requestData['age']) || $requestData['age'] < 18) {
                            $bool = false;
                        }

                        if (!strlen($requestData['passport_exp']) || $day > $requestData['passport_exp']) {
                            $bool = false;
                        }

                        if (!strlen($requestData['visa_exp_date']) || $day > $requestData['visa_exp_date']) {
                            $bool = false;
                        }

                        break;
                    }
            }
            // foreach(\Api\Model\Service\Core::$brokers as $broker){
            //         $allbrokers[] = $broker;
            // }
            // foreach(\Api\Model\Service\Core::$required_documents as $document){
            //     $documents[] = $document;
            // }   
            $allbrokers['name'] = \Api\Model\Service\Core::$brokers;
            $documents['name'] = \Api\Model\Service\Core::$required_documents;
            $first_name = $requestData['first_name'];
            $checked = 1;
            $data['checked'] = $checked;
            $data['bool'] = $bool;
            $data['first_name'] = $first_name;
            $data['brokers'] = [];
            $data['documents'] = [];
            if($bool){
                $data['brokers'] = $allbrokers;
                $data['documents'] = $documents;
            }
         
              
       
            if (!empty($data)) {
                $this->apiResponse['data'] = $data;
            } else {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'error';
            }             
           
        }
        else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'insert form data';
        }
    }
     /**
     * getNotifications method.
     *
     * @return string
     */
    public function getNotifications()
    {
        if ($this->jwtPayload->id) {
            $this->loadModel('Api.Notifications');
            $notifications = $this->Notifications->find('all')
                ->order(['seen ASC'])
                ->order(['created_at DESC'])
                ->where(['user_id' => $this->jwtPayload->id])
                ->where(['seen' => 0]);

            $count_unread_notifications = $this->Notifications->find('all')
                    ->where(['user_id' => $this->jwtPayload->id])
                    ->where(['seen' => 0])->count();

            $data['count'] = $count_unread_notifications; 
            $data['notifications'] = $notifications; 

            if (!empty($data)) {
                $this->apiResponse['data'] = $data;
            } else {
                $this->apiResponse['data'] = [];
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'not logged in';
        }
    }

    public function readNotification()
    {
        if ($this->jwtPayload->id) {
            $this->request->allowMethod(['post']);
            $id = $this->request->getData('notification_id');

            $user_id = $this->jwtPayload->id;

            $this->loadModel('Api.Notifications');
            $notification_data = $this->Notifications->find('all')->where(['id' => $id, 'user_id' => $user_id])->first();
            if ($notification_data) {
                $bool = $this->Notifications->changeSeenStatus($id, 1);
                if ($bool) {
                    $this->apiResponse['message'] = 'ok';
                } else {
                    $this->apiResponse['error'] = 'error';
                }
            }
            else {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'no notification found';
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'not logged in';
        }
    }

}
