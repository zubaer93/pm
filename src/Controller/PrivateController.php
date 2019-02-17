<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;
use CakeDC\Users\Controller\UsersController;
use Cake\Core\Configure;

class PrivateController extends UsersController
{

    public function privatePost()
    {
        if (!$this->Auth->user('id')) {
            $this->redirect(['_name' => 'login']);
        }
        $this->profile();
        $userId = $this->Auth->user('id');
        $this->loadModel('Follow');
        $following = $this->Follow->find('all')
                ->contain('Following')
                ->where(['Follow.user_id' => $userId])
                ->order(['Follow.created_at' => 'desc']);

        $array = [];
        foreach ($following as $val) {
            $array[] = $val['following']['id'];
        }
        if (count($array)) {
            $Users = TableRegistry::get('Users');
            $all_users = $Users->find('list', array('fields' => array('username', 'id')))
                    ->where(['id IN' => $array]);
        } else {
            $all_users = [];
        }

        $this->set(compact('all_users'));
    }

    public function privateSave()
    {
        $this->authUser();
        $this->loadModel('AppUsers');
        $this->loadModel('Notifications');
        $this->loadModel('Private');
        $this->loadModel('UserInvite');
        if (isset($this->request) && $this->request->allowMethod(['post'])) {
            $result = $this->Private->setPrivate($this);
            if ($result['result']) {
                $data = ['id' => $result['id'], 'user_id' => $this->request->getData('private_user')];
                $invite = $this->UserInvite->setUserInvite($data);
                $privateGet = $this->Private->get($result['id']);
                foreach ($this->request->getData('private_user') as $userId) {
                    $referer = $this->_getCurrentLanguage();
                    $url = $this->_getUrl();
                    $url = parse_url($url, PHP_URL_HOST);
                    $url = '/' . $referer . '/private/' . $privateGet->slug;
                    $this->Notifications->user_id = $userId;
                    $this->Notifications->url = $url;
                    $this->Notifications->title = $this->Auth->user('username') . ' - ' . implode(' ', array_slice(explode(' ', "Private $privateGet->name "), 0, 5)) . '...';
                    $bool = $this->Notifications->setNotification($this->Notifications);
                }
            }
            return $this->redirect(['action' => 'privatePost']);
        }
    }

    public function ajaxManagePrivateFrontSearch()
    {
        $id = $this->Auth->user('id');
        $requestData = $this->request->getQuery();
        $obj = new \App\Model\DataTable\PrivateDataTable();
        $result = $obj->ajaxManagePrivateFrontSearch($requestData, $id);
        echo $result;
        exit;
    }

    public function privateRoom($room)
    {
        $id = $this->Auth->user('id');

        if (isset($room) && $id) {
            $private = $this->Private->find()->where(['slug =' => $room])->first();
            if (is_null($private)) {
                return $this->redirect(['_name' => 'home']);
            }
            $private_id = $private->id;
            $this->loadModel('UserInvite');
            $this->loadModel('Messages');
            $data = $this->UserInvite->find()->where(['user_id' => $id])->andWhere(['invite_id' => $private_id])->first();
            if ($private->user_id === $id || !is_null($data)) {
                $this->loadModel('News');
                $news = $this->News->getNews($this->_getCurrentLanguage(), false);

                $this->loadModel('Countries');
                $countryName = $this->Countries->getName($this->_getCurrentLanguage());

                $userId = $this->Auth->user('id');
                $currentLanguage = $this->_getCurrentLanguageId();

                if ($this->Auth->user()) {
                    $avatarPath = $this->Messages->AppUsers->get($this->Auth->user('id'))->avatarPath;
                    if ($avatarPath == Configure::read('Users.Avatar.placeholder')) {
                        $avatarPath = Configure::read('Users.avatar.default');
                    }
                } else {
                    $avatarPath = '';
                }
                ///this is for ajax///

                $page_is = 'private';
                $page_data = $private_id;

                //////////////////////

                $this->set(compact('messages', 'news', 'countryName', 'userId', 'currentLanguage', 'avatarPath', 'page_is', 'page_data', 'private_id'));
                $this->set('_serialize', ['messages']);
            } else {
                return $this->redirect(['_name' => 'home']);
            }
        } else {
            return $this->redirect(['_name' => 'home']);
        }
    }

    public function privateEdit($edit)
    {

        $this->privatePost();
        $data = $this->Private->find()->where(['Private.slug' => $edit])
                        ->contain([
                            'UserInvite' => function ($q)
                            {
                                return $q->autoFields(false)
                                        ->contain(['AppUsers' => function ($q)
                                            {
                                                return $q->autoFields(false)
                                                        ->select('AppUsers.username');
                                            }
                                ]);
                            },
                        ])->toArray();
        $users = array();
        foreach ($data as $row) {
            foreach ($row['user_invite'] as $appuser) {
                $users[] = $appuser->user_id;
            }
        }
        $data = $data[0];
        $this->set(compact('users', 'data'));
    }

    public function editPost()
    {

        if (isset($this->request) && $this->request->allowMethod(['post'])) {
            $result = $this->Private->update($this->request->getData());
            if ($result['result']) {
                $this->loadModel('UserInvite');
                $this->UserInvite->update($this->request->getData());
            }
            return $this->redirect(['action' => 'privatePost']);
        }
    }

    public function privateDeleteRoom()
    {
        if ($this->request->is('get') && !is_null($this->request->getQuery())) {
            $entity = $this->Private->get($this->request->getQuery('message_id'));
            $result = $this->Private->delete($entity);
            if ($result) {
                $response = [
                    'status' => 'success',
                ];
                return $this->response;
            }
        }
    }

    protected function authUser()
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
    }

}
