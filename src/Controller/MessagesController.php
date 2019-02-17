<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use App\Model\Table\RatingsTable;

class MessagesController extends AppController
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
        $this->components()->unload('Csrf');
    }

    public function index()
    {
        $newsfeed = $this->Messages->newEntity();
        $user = $this->Messages->AppUsers->get($this->Auth->user('id'));

        $this->set(compact('newsfeed', 'user'));
        $this->set('_serialize', ['newsfeed', 'user']);
    }

    /**
     * refreshComment method this method will serialize the results to show in the feed
     *
     * @return void
     */
    public function refreshComment($requestData = null)
    {
        if ($this->request->is('ajax')) {
            $i = 0;
            if (is_null($requestData)) {
                $requestData = $this->request->getData();
            } else {
                $i = 1;
            }
            $array = [];
            $remove = [];
            $messages_id = [0];
            $comment_id = [0];
            $new_messages = [];
            if (isset($requestData['messages_data'])) {
                $messages_data = $requestData['messages_data'];
                foreach ($messages_data as $val) {

                    $result = $this->getMessages($val['id']);
                    $messages_id[] = $val['id'];
                    if (!is_null($result)) {
                        $comment_id[] = $result['comment_id'];
                        if (!$this->getChangedOrNot($result, $val)) {
                            $array[] = $this->getMessageResponse($result);
                        }
                    } else {
                        $remove[]['id'] = $val['id'];
                    }
                }
            }

            $new_messages = $this->newMessagess($requestData, $messages_id, $comment_id);

            $response = [
                'status' => 'success',
                'data' => $array,
                'remove' => $remove,
                'new' => $new_messages,
                'message' => 'Successful!'
            ];
            $this->response->statusCode(200);
            $this->setJsonResponse($response);

            return $this->response;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'error!'
            ];
            $this->response->statusCode(404);
            $this->setJsonResponse($response);

            return $this->response;
        }
    }

    /**
     * refresh_messages method this method will serialize the results to show in the feed
     *
     * @return void
     */
    public function refreshMessages($requestData = null)
    {
        if ($this->request->is('ajax')) {
            $i = 0;
            if (is_null($requestData)) {
                $requestData = $this->request->getData();
            } else {
                $i = 1;
            }
            $array = [];
            $remove = [];
            $messages_id = [0];
            $new_messages = [];
            if (isset($requestData['messages_data'])) {
                $messages_data = $requestData['messages_data'];
                foreach ($messages_data as $val) {

                    $result = $this->getMessages($val['id']);

                    $messages_id[] = $val['id'];
                    if (!is_null($result)) {
                        if (!$this->getChangedOrNot($result, $val)) {
                            $array[] = $this->getMessageResponse($result);
                        }
                    } else {
                        $remove[]['id'] = $val['id'];
                    }
                }
            }
            $new_messages = $this->newMessagess($requestData, $messages_id);
            $response = [
                'status' => 'success',
                'data' => $array,
                'remove' => $remove,
                'new' => $new_messages,
                'message' => 'Successful!'
            ];
            $this->response->statusCode(200);
            $this->setJsonResponse($response);

            return $this->response;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'error!'
            ];
            $this->response->statusCode(404);
            $this->setJsonResponse($response);

            return $this->response;
        }
    }

    private function getMessageResponse($message)
    {
        $data_img = null;
        $url = null;

        if (count($message['screenshot_message'])) {
            $screenshot_message = reset($message['screenshot_message']);
            $data_img = $screenshot_message['data'];
            $url = $screenshot_message['url'];
        }
        $MessageData = null;

        if (!is_null($message->parent_id)) {
            $MessageData = $this->Messages->getMessage($message->parent_id);
        }

        $user_id = $this->Auth->user('id');

        $delete_status = 'hidden';

        if ($message->app_user->id == $user_id) {
            $delete_status = '';
        }

        if (is_null($MessageData)) {
            $array = [
                'status' => 'success',
                'data' => $this->Messages->getFeedData($message, $message->app_user),
                'avatar_path' => (file_exists(WWW_ROOT . $message->app_user->avatarPath)) ? $message->app_user->avatarPath : (('CakeDC/Users.avatar_placeholder.png' == $message->app_user->avatar ) ? ('/cake_d_c/users/img/avatar_placeholder.png') : $message->app_user->avatar),
                'img_page' => $data_img,
                'delete_status' => $delete_status,
                'url_page' => $url,
                'message' => 'Message save successful!'
            ];
        } else {
            $this->loadModel('AppUsers');
            $UserData = $this->AppUsers->getUserInfoWithId($MessageData['user_id']);

            $ScreenshotMessage = TableRegistry::get('ScreenshotMessage');
            $data_parent = $ScreenshotMessage->getMessageData($message['parent_id']);
            if (is_null($data_parent)) {
                $data_img_parent = null;
                $data_url_parent = null;
            } else {
                $data_img_parent = $data_parent['data'];
                $data_url_parent = $data_parent['url'];
            }
            $array = [
                'status' => 'success',
                'data' => $this->Messages->getFeedData($message, $message->app_user),
                'avatar_path' => (file_exists(WWW_ROOT . $message->app_user->avatarPath)) ? $message->app_user->avatarPath : (('CakeDC/Users.avatar_placeholder.png' == $message->app_user->avatar ) ? ('/cake_d_c/users/img/avatar_placeholder.png') : $message->app_user->avatar),
                'img_page' => $data_img,
                'url_page' => $url,
                'img_data_parent' => $data_img_parent,
                'delete_status' => $delete_status,
                'url_data_parent' => $data_url_parent,
                'parent' => $this->Messages->getFeedData($MessageData, $UserData),
                'parent_avatar' => (file_exists(WWW_ROOT . $UserData->avatarPath)) ? $UserData->avatarPath : (('CakeDC/Users.avatar_placeholder.png' == $UserData->avatar ) ? ('/cake_d_c/users/img/avatar_placeholder.png') : $UserData->avatar),
                'message' => 'Message save successful!'
            ];
        }
        return $array;
    }

    private function getChangedOrNot($message, $val)
    {
        $bool = true;
        if ($message->created->nice() != $val['date']) {
            $bool = false;
        } elseif (!empty($val['rate']) && RatingsTable::getAverageRanking($message->id) != (int) $val['rate']) {
            $bool = false;
        } elseif ($message->app_user->username . ' -' != $val['username']) {
            $bool = false;
        } elseif ($message->app_user->experience_by_id != $val['experience']) {
            $bool = false;
        } elseif ($message->app_user->investment_style_by_id != $val['investment']) {
            $bool = false;
        } elseif (preg_replace('/\s+/', '', Hash::get($val, 'message')) != preg_replace('/\s+/', '', $message->message)) {
            $bool = false;
        }

        return $bool;
    }

    private function getMessages($id)
    {
        return $this->Messages
            ->find('all')
            ->where(['Messages.id' => $id])
            ->contain('AppUsers')
            ->contain(['Ratings'])
            ->contain(['ScreenshotMessage'])
            ->first();
    }

    private function newMessagess($requestData, $messages_id, $comment_id = null)
    {
        if (!isset($requestData['page'])) {
            $requestData['page'] = 'all';
        }
        if (is_null($comment_id)) {
            $where = ['Messages.comment_id IS' => NULL];
        } else {
            $where = ['Messages.comment_id IN' => $comment_id];
        }
        switch ($requestData['page']) {
            case 'all': {
                $messages = $this->Messages->find('all')
                    ->where(['country_id' => $this->_getCurrentLanguageId()])
                    ->where(['Messages.id NOT IN' => $messages_id])
                    ->where(['Messages.private_id IS' => NULL])
                    ->where($where)
                    ->contain('AppUsers')
                    ->contain(['Ratings'])
                    ->contain(['ScreenshotMessage'])
                    ->order(['Messages.created' => 'desc'])
                    ->toArray();
                break;
            }
            case 'company': {
                $this->loadModel('Companies');
                $symbol = $this->Companies->get($requestData['data']);
                $messages = $this->Messages->find()
                    ->where([
                        'OR' => [
                            ['company_id' => $requestData['data']],
                            ['BINARY (message) LIKE' => '%' . $symbol['symbol'] . '%']
                        ]
                    ])
                    ->where($where)
                    ->where(['Messages.id NOT IN' => $messages_id])
                    ->where(['Messages.private_id IS' => NULL])
                    ->contain('AppUsers')
                    ->contain(['Ratings'])
                    ->contain(['ScreenshotMessage'])
                    ->order(['Messages.created' => 'desc'])
                    ->toArray();
                break;
            }
            case 'trader': {
                $this->loadModel('Trader');
                $exchangeInfo = $this->Trader->__getTraderInfoFromCurrency($requestData['data']);
                $messages = $this->Messages->find('all')
                    ->where([
                        'OR' => [
                            ['trader_id' => $exchangeInfo['id']],
                            ['message LIKE' => '% ' . $exchangeInfo['from_currency_code'] . ' %'],
                            ['message LIKE' => '% ' . $exchangeInfo['to_currency_code'] . ' %'],
                            ['message LIKE' => '% ' . $exchangeInfo['from_currency_name'] . ' %'],
                            ['message LIKE' => '% ' . $exchangeInfo['to_currency_name'] . ' %'],
                        ]
                    ])
                    ->where($where)
                    ->where(['Messages.id NOT IN' => $messages_id])
                    ->where(['Messages.private_id IS' => NULL])
                    ->contain('AppUsers')
                    ->contain(['Ratings'])
                    ->contain(['ScreenshotMessage'])
                    ->order(['Messages.created' => 'desc'])
                    ->toArray();
                break;
            }
            case 'user': {
                $userId = $this->Auth->user('id');
                $messages = $this->Messages->find('all')
                    ->where(['user_id' => $userId])
                    ->where(['Messages.id NOT IN' => $messages_id])
                    ->where(['Messages.private_id IS' => NULL])
                    ->where($where)
                    ->contain('AppUsers')
                    ->contain(['Ratings'])
                    ->contain(['ScreenshotMessage'])
                    ->order(['Messages.created' => 'desc'])
                    ->toArray();
                break;
            }
            case 'private': {
                $private_id = $requestData['data'];
                $messages = $this->Messages->find('all')
                    ->where($where)
                    ->where(['Messages.country_id' => $this->_getCurrentLanguageId()])
                    ->where(['Messages.private_id' => $private_id])
                    ->order(['Messages.modified DESC'])
                    ->where(['Messages.id NOT IN' => $messages_id])
                    ->contain('AppUsers')
                    ->contain(['Ratings'])
                    ->contain(['ScreenshotMessage'])
                    ->toArray();
                break;
            }
        }

        $array = [];
        foreach ($messages as $message) {
            $array[] = $this->getMessageResponse($message);
        }
        return $array;
    }

    public function dashboard()
    {
        $this->loadModel('News');
        $news = $this->News->getNews($this->_getCurrentLanguage(), false);

        $this->loadModel('Countries');
        $countryName = $this->Countries->getName($this->_getCurrentLanguage());

        $userId = $this->Auth->user('id');
        $currentLanguage = $this->_getCurrentLanguageId();

        $this->loadModel('SectorPerformances');
        $sector = $this->SectorPerformances->getSectorPerformances($this->_getCurrentLanguage());

        $this->loadModel('WatchlistGroup');

        $stockWatchlists = $this->WatchlistGroup->getWatchlists($userId);

        if ($this->Auth->user()) {
            $avatarPath = $this->Messages->AppUsers->get($this->Auth->user('id'))->avatarPath;
            if ($avatarPath == Configure::read('Users.Avatar.placeholder')) {
                $avatarPath = Configure::read('Users.avatar.default');
            }
        } else {
            $avatarPath = '';
        }
        ///this is for ajax///

        $page_is = 'all';
        $page_data = $this->_getCurrentLanguageId();

        //////////////////////

        $this->set(compact('sector', 'news', 'countryName', 'userId', 'currentLanguage', 'avatarPath', 'page_is', 'page_data', 'stockWatchlists'));
        $this->set('_serialize', ['messages']);
    }

    public function addComment()
    {
        if ($this->request->is('ajax')) {
            if ($this->Auth->user()) {
                $requestData = $this->request->getData();
                $message_id = $requestData['message_id'];
                $message_body = $requestData['message'];
                $comment_paret_message = $this->Messages->get($message_id);

                $message = $this->Messages->newEntity();
                $message->message = $message_body;
                $message->user_id = $this->Auth->user('id');
                $message->company_id = $comment_paret_message['company_id'];
                $message->trader_id = $comment_paret_message['trader_id'];
                $message->country_id = $comment_paret_message['country_id'];
                $message->comment_id = $message_id;
                if (!$this->Messages->save($message)) {
                    $response = [
                        'status' => 'error',
                        'data' => null,
                        'img_page' => null,
                        'url_page' => null,
                        'message' => 'Error on save message'
                    ];
                } else {
                    $this->sendNotifications($message);
                    $response = [
                        'status' => 'success',
                        'data' => $this->Messages->getCommentsData($message, $this->Auth->user()),
                        'message' => 'Message save successful!'
                    ];
                }
                $this->response->statusCode(200);
                $this->setJsonResponse($response);
                return $this->response;
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'error!'
            ];
            $this->response->statusCode(404);
            $this->setJsonResponse($response);

            return $this->response;
        }
    }
    public function postUploadImg(){
        $response = $this->Messages->uploadFile($this->request->data);
        print_r($response);
        die();
    }
    public function post()
    {
        if (!$this->Auth->user()) {
            $response = [
                'status' => 'error',
                'data' => null,
                'message' => 'Not logged in'
            ];
            $this->response->statusCode(401);
            $this->setJsonResponse($response);
            return $this->response;
        }

        $message = $this->Messages->newEntity();
        $requestData = $this->request->getData();
        $this->request->allowMethod(['post']);
        $message->message = $requestData['message'];
        $message->user_id = $requestData['user_id'];
        $message->company_id = $requestData['company_id'];
        $message->trader_id = $requestData['trader_id'];
        $message->country_id = $requestData['market'];
        $message->private_id = $requestData['private_id'];
        //$message->attachment = $requestData['attachment'];
        if(isset($requestData['attachment']) ){ $message->attachment = $requestData['attachment']; }
        
        if (!$this->Messages->save($message)) {
            $response = [
                'status' => 'error',
                'data' => null,
                'img_page' => null,
                'url_page' => null,
                'message' => 'Error on save message'
            ];
        } else {
            $matches = [];
            preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $message->message, $matches);
            $data_img = null;
            $url = null;
            if (isset($matches[0]) && count($matches[0])) {
                $url = end($matches[0]);
                if (\App\Model\Scrapper\Core::checkUrl($url)) {
                    $data_img = $this->getScreenshot($url);
                    $this->loadModel('ScreenshotMessage');
                    $screenshot_message = $this->ScreenshotMessage->newEntity();
                    $screenshot_message->message_id = $message['id'];
                    $screenshot_message->data = $data_img;
                    $screenshot_message->url = $url;
                    $this->ScreenshotMessage->save($screenshot_message);
                }
                $matches = null;
            }
            $response = [
                'status' => 'success',
                'data' => $this->Messages->getFeedData($message, $this->Auth->user()),
                'img_page' => $data_img,
                'url_page' => $url,
                'message' => 'Message save successful!'
            ];

            $client = Configure::read('client_getstream');
            $user = $client->feed('user', $message->user_id);
            $data = array(
                "actor" => $message->user_id,
                "verb" => "post",
                "object" => "send:message",
                "foreign_id" => "stock:1",
                "message" => $message->message
            );

            $user->addActivity($data);

            $this->sendNotifications($message);
        }

        $this->setJsonResponse($response);
        return $this->response;
    }

    public function getMessageInfo()
    {
        if (!$this->Auth->user()) {
            $response = [
                'status' => 'error',
                'data' => null,
                'message' => 'Not logged in'
            ];
            $this->response->statusCode(401);
            $this->setJsonResponse($response);
            return $this->response;
        }
        $message_id = $this->request->params['?']['message_id'];
        $MessageData = $this->Messages->getMessage($message_id);
        $this->loadModel('AppUsers');

        $UserData = $this->AppUsers->getUserInfoWithId($MessageData['user_id']);

        $response = [
            'status' => 'success',
            'data' => $this->Messages->getFeedData($MessageData, $UserData),
            'message' => 'Successful!'
        ];
        $this->setJsonResponse($response);
        return $this->response;
    }

    /**
     * postModal() method will save share message
     *
     * @return json
     */
    public function postModal()
    {
        if ($this->request->is('ajax')) {
            if (!$this->Auth->user()) {
                $response = [
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Not logged in'
                ];
                $this->response->statusCode(401);
                $this->setJsonResponse($response);
                return $this->response;
            }

            $message = $this->Messages->newEntity();

            $this->request->allowMethod(['post']);

            $message->message = $this->request->getData('message');
            $message->user_id = $this->request->getData('user_id');
            $message->parent_id = $this->request->getData('parent_id');
            $message->company_id = $this->request->getData('company_id');
            $message->trader_id = $this->request->getData('trader_id');
            $message->country_id = $this->request->getData('market');
            if (!$this->Messages->save($message)) {
                $response = [
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Error on save message'
                ];
            } else {
                $matches = [];
                preg_match_all('@((https?://)?([-\\w]+\\.[-\\w\\.]+)+\\w(:\\d+)?(/([-\\w/_\\.]*(\\?\\S+)?)?)*)@', $message->message, $matches);
                $data_img = null;
                $url = null;
                if (isset($matches[0]) && count($matches[0])) {
                    $data_img = $this->getScreenshot(end($matches[0]));
                    $url = end($matches[0]);
                    $this->loadModel('ScreenshotMessage');
                    $screenshot_message = $this->ScreenshotMessage->newEntity();
                    $screenshot_message->message_id = $message['id'];
                    $screenshot_message->data = $data_img;
                    $screenshot_message->url = $url;
                    $this->ScreenshotMessage->save($screenshot_message);
                    $matches = null;
                }
                $MessageData = $this->Messages->getMessage($this->request->getData('parent_id'));
                $this->loadModel('AppUsers');
                $UserData = $this->AppUsers->getUserInfoWithId($MessageData['user_id']);
                $ScreenshotMessage = TableRegistry::get('ScreenshotMessage');
                $data_parent = $ScreenshotMessage->getMessageData($message['parent_id']);

                if (is_null($data_parent)) {
                    $data_img_parent = null;
                    $data_url_parent = null;
                } else {
                    $data_img_parent = $data_parent['data'];
                    $data_url_parent = $data_parent['url'];
                }
                $response = [
                    'status' => 'success',
                    'data' => $this->Messages->getFeedData($message, $this->Auth->user()),
                    'img_page' => $data_img,
                    'url_page' => $url,
                    'img_data_parent' => $data_img_parent,
                    'url_data_parent' => $data_url_parent,
                    'parent' => $this->Messages->getFeedData($MessageData, $UserData),
                    'parent_avatar' => $this->request->getData('parent_img_url'),
                    'message' => 'Message save successful!'
                ];

                $this->sendNotifications($message);
            }

            $this->setJsonResponse($response);
            return $this->response;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'error!'
            ];
            $this->response->statusCode(404);
            $this->setJsonResponse($response);

            return $this->response;
        }
    }

    protected function setJsonResponse($response)
    {
        $response = json_encode($response);
        $this->response->type('application/json');
        $this->response->body($response);
    }

    /**
     * edit method Override edit user method Updates too the description of social accounts
     *
     * @param $id string user id
     * @return mixed
     */
    public function edit($id = null)
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

            return $this->redirect(['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'profile']);
        }
        $this->Flash->error(__d('CakeDC/Users', 'The {0} could not be saved', 'user'));
    }

    public function add()
    {
        $this->request->allowMethod(['post']);

        $this->Participant->set($this->request->data);

        if (!$this->Participant->save($this->Participant->data)) {
            $response = array(
                'status' => 'error',
                'data' => null,
                'message' => 'Não foi possível salvar suas informações'
            );

            $this->set('response', $response);
            return $this->set('_serialize', array('response'));
        }

        $response = array(
            'status' => 'success',
            'data' => null,
            'message' => 'Suas informações foram salvas com sucesso!'
        );

        $this->set('response', $response);
        return $this->set('_serialize', array('response'));
    }

    public function addReting()
    {
        if ($this->request->is('ajax')) {
            if (!$this->Auth->user()) {
                $response = [
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Not logged in'
                ];
                $this->response->statusCode(401);
                $this->setJsonResponse($response);
                return $this->response;
            }
            $getData = $this->request->getData();

            $this->loadModel('Ratings');
            $result = $this->Ratings->setRating($getData, $this->Auth->user('id'));
            if (!$result) {
                $response = [
                    'status' => 'error'
                ];
                $this->response->statusCode(424);
                $this->setJsonResponse($response);
                return $this->response;
            } else {
                $response = [
                    'status' => 'success',
                    'rating' => RatingsTable::getAverageRanking($getData['message_id']) . '.0'
                ];
                $this->response->statusCode(200);
                $this->setJsonResponse($response);
                return $this->response;
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'error!'
            ];
            $this->response->statusCode(404);
            $this->setJsonResponse($response);

            return $this->response;
        }
    }

    public function deletePost()
    {
        if ($this->request->is('ajax')) {
            if (!$this->Auth->user()) {
                $response = [
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Not logged in'
                ];
                $this->response->statusCode(401);
                $this->setJsonResponse($response);
                return $this->response;
            }
            $attachment = $this->Messages->find()->select(['attachment'])->where(['id' => $this->request->data('message_id')])
                        ->first();
                $file = WWW_ROOT . 'upload/attachment_folder/'.$attachment->attachment;
                echo $file;
               // die();
                unlink($file);
                if ($this->request->is('post') && !is_null($this->request->getData())) {

                $user_id = $this->Auth->user('id');
                
                $message_data = $this->Messages->find()->where(['id' => $this->request->data('message_id')])
                        ->first();
                if ($message_data['user_id'] == $user_id) {
                    $ScreenshotMessage = TableRegistry::get('ScreenshotMessage');
                    $ScreenshotMessage->deleteRow($this->request->data('message_id'));
                    $result = $this->Messages->deletMessageWithAdmin($this->request->data('message_id'));
                    if ($result) {
                        $response = [
                            'status' => 'success'
                         ];
                        $this->response->statusCode(200);

                        $this->setJsonResponse($response);
                        return $this->response;
                    }
                }
            }
            $response = [
                'status' => 'error'               
            ];
            $this->response->statusCode(424);
            $this->setJsonResponse($response);
            return $this->response;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'error!'
            ];
            $this->response->statusCode(404);
            $this->setJsonResponse($response);

            return $this->response;
        }
    }

    public function readNotification()
    {
        if ($this->request->is('ajax')) {
            if (!$this->Auth->user()) {
                $response = [
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Not logged in'
                ];
                $this->response->statusCode(401);
                $this->setJsonResponse($response);
                return $this->response;
            }
            $this->request->allowMethod(['post']);
            $id = $this->request->getData('notification_id');

            $user_id = $this->Auth->user('id');

            $this->loadModel('Notifications');
            $notification_data = $this->Notifications->find('all')->where(['id' => $id, 'user_id' => $user_id])->first();
            if ($notification_data) {
                $bool = $this->Notifications->changeSeenStatus($id, 1);
                $response = [
                    'notification_id' => $id,
                    'status' => 'success'
                ];
                $this->response->statusCode(200);

                $this->setJsonResponse($response);
                return $this->response;
            }
            $response = [
                'status' => 'error'
            ];
            $this->response->statusCode(424);
            $this->setJsonResponse($response);
            return $this->response;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'error!'
            ];
            $this->response->statusCode(404);
            $this->setJsonResponse($response);

            return $this->response;
        }
    }

    public function getScreenshot($src)
    {
        $siteURL = trim($src);
        $googlePagespeedData = @file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$siteURL&screenshot=true&strategy=desktop");

        $googlePagespeedData = json_decode($googlePagespeedData, true);
        $screenshot = $googlePagespeedData['screenshot']['data'];
        $screenshot = str_replace(array('_', '-'), array('/', '+'), $screenshot);
        return $screenshot;
    }

    public function getPostComment()
    {
        if ($this->request->is('ajax')) {
            $requestDatat = $this->request->getData();

            $result = $this->Messages->getPostComment($requestDatat['message_id']);
            $array = [];
            foreach ($result as $val) {
                $array[] = [
                    'status' => 'success',
                    'data' => $this->Messages->getCommentsData($val, $val->app_user),
                    'avatar_path' => (file_exists(WWW_ROOT . $val->app_user->avatarPath)) ? $val->app_user->avatarPath : (('CakeDC/Users.avatar_placeholder.png' == $val->app_user->avatar ) ? ('/cake_d_c/users/img/avatar_placeholder.png') : $val->app_user->avatar),
                    'message' => 'Message save successful!'
                ];
            }
            $response = [
                'status' => 'successful',
                'data' => $array,
                'message' => 'successful'
            ];
            $this->response->statusCode(200);
            $this->setJsonResponse($response);
            return $this->response;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'error!'
            ];
            $this->response->statusCode(404);
            $this->setJsonResponse($response);

            return $this->response;
        }
    }

    public function getPostCommentsCount()
    {
        if ($this->request->is('ajax')) {
            $requestData = $this->request->getData();
            $array = [];
            if (!empty($requestData)) {
                foreach ($requestData['messages_data'] as $key => $val) {
                    $count = $this->Messages->getPostCommentsCount($val['id']);
                    $array[$key]['id'] = $val['id'];
                    $array[$key]['count'] = $count;
                }
            }

            $response = [
                'status' => 'successful',
                'data' => $array,
                'message' => 'successful'
            ];
            $this->response->statusCode(200);
            $this->setJsonResponse($response);
            return $this->response;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'error!'
            ];
            $this->response->statusCode(404);
            $this->setJsonResponse($response);

            return $this->response;
        }
    }

    /**
     * sendNotifications method will be send to those people who were noted in the message
     *
     * @param $message object
     * @return mixed
     */
    private function sendNotifications($message)
    {
        $matches = [];
        preg_match_all('/@(\w+)/', $message->message, $matches);
        if (!empty($matches[1])) {

            $this->loadModel('AppUsers');
            $this->loadModel('Notifications');
            foreach ($matches[1] as $match) {
                $userData = $this->AppUsers->getUserInfo($match);

                if (!is_null($userData)) {
                    $this->Notifications->user_id = $userData->id;
                    $this->Notifications->url = $this->referer();
                    $this->Notifications->title = $this->Auth->user('username') . ' - ' . implode(' ', array_slice(explode(' ', $message->message), 0, 5)) . '...';
                    $bool = $this->Notifications->setNotification($this->Notifications);
                    $usersEmail = new \App\Mailer\UsersMailer();
                    try {
                        $email = new Email();
                        $email
                                ->template('sendNotification')
                                ->emailFormat('html')
                                ->to($userData['email'])
                                ->from('app@domain.com')
                                ->subject('Someone noticed you in a post')
                                ->viewVars(['email' => $userData['email'],
                                    'from_username' => $this->Auth->user()['username'],
                                    'to_username' => $userData['username'],
                                    'to_first_name' => $userData['first_name'],
                                    'to_last_name' => $userData['last_name'],
                                    'url' => $this->referer(),
                                    'message' => implode(' ', array_slice(explode(' ', $message->message), 0, 5)),
                                ])
                                ->send();
                    } catch (\Exception $e) {
                        
                    }
                }
            }
        }
    }

}
