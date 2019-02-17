<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Admin;

use CakeDC\Users\Controller\Component\UsersAuthComponent;
use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * Description of UsersController
 *
 * @author Karen
 */
class UsersController extends AppController
{
    /**
     * get all
     *
     */
    public function all()
    {
        
    }

    public function add()
    {
        if ($this->request->is('post') && !is_null($this->request->getData())) {
            $usersTable = $this->loadModel('AppUsers');
            $user = $usersTable->newEntity();
            $validateEmail = (bool) Configure::read('Users.Email.validate');
            $useTos = (bool) Configure::read('Users.Tos.required');
            $tokenExpiration = Configure::read('Users.Token.expiration');
            $options = [
                'token_expiration' => $tokenExpiration,
                'validate_email' => false,
                'use_tos' => false
            ];
            $requestData = $this->request->getData();

            $userSaved = $usersTable->register($user, $requestData, $options);

            if (!$userSaved) {
                $this->Flash->error(__d('CakeDC/Users', 'The user could not be saved'));

                return;
            } else {
                return $this->redirect(['_name' => 'users_list']);
            }
        }
    }

    public function edit($id)
    {
        $table = $this->loadModel('AppUsers');
        if ($this->request->is('post') && !is_null($this->request->getData())) {
      
            $tableAlias = $table->alias();
            $entity = $table->get($id, [
                'contain' => []
            ]);
            $this->set($tableAlias, $entity);
            $this->set('tableAlias', $tableAlias);
            $this->set('_serialize', [$tableAlias, 'tableAlias']);
            $entity = $table->patchEntity($entity, $this->request->getData());
            $entity->role = $this->request->getData('role');

            if ($table->save($entity)) {
                $this->Flash->success(__d('CakeDC/Users', 'The {0} has been saved', 'user'));

                return $this->redirect(['_name' => 'users_list']);
            }
            $this->Flash->error(__d('CakeDC/Users', 'The {0} could not be saved', 'user'));
        }
        $user = $table->get($id);

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * editAvatar method Upload a image file and move to /upload/avatar
     *
     * @param $id string user id
     * @return redirect to profile page
     */
    public function editAvatar($id)
    {
        $this->loadModel('AppUsers');
        $user = $this->AppUsers->get($id);
        if ($this->request->is('post')) {
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

            return $this->redirect(['_name' => 'users_list']);
        }
    }

    public function delete($id)
    {   
        $this->loadModel('AppUsers');
        $this->loadModel('IpoInterestedUsers');
        $ipoUsers = $this->IpoInterestedUsers->find()->where(['app_user_id' => $id])->toArray();
        if($ipoUsers){
            $this->IpoInterestedUsers->delete($ipoUsers);
        }
        $user = $this->AppUsers->get($id);
        $result = $this->AppUsers->delete($user);
        if ($result) {
            $this->Flash->success(__('User is deleted.'));
        } else {
            $this->Flash->error(__('User was not deleted. Try again'));
        }

        $this->redirect(['_name' => 'users_list']);
    }

    /**
     * 
     * @param type $id
     */
    public function disable($id)
    {
        $this->loadModel('AppUsers');
        $result = $this->AppUsers->disableUser($id);

        if ($result) {
            $this->Flash->success(__('User is disabled.'));
        } else {
            $this->Flash->error(__('User was not disabled.'));
        }

        $this->redirect(['_name' => 'users_list']);
    }

    /**
     * 
     * @param type $id
     */
    public function enable($id)
    {

        $this->loadModel('AppUsers');
        $result = $this->AppUsers->enableUser($id);

        if ($result) {
            $this->Flash->success(__('User is enabled.'));
        } else {
            $this->Flash->error(__('User was not enabled.'));
        }

        $this->redirect(['_name' => 'users_list']);
    }

    public function ajaxManageUserSearch()
    {
        $this->components()->unload('Csrf');
        $requestData = $this->request->getData();

        $obj = new \App\Model\DataTable\UserDataTable();
        $result = $obj->ajaxManageUserSearch($requestData);

        echo $result;
        exit;
    }
    public function sendPromo(){
        $this->loadModel('AppUsers');
        $email = $this->request->getData('email');
        $user = $this->AppUsers->find()->where(['email' => $email])->first();

        $invitation_code = strtoupper(substr(md5(uniqid($user['id'], true)), 22, 32)); //generate a unique code
        $date = strtotime("+14 day");
        $inv_code_exp = date('d M Y', $date);
        $options = array('template' => 'invitation_code',
                'to' => $user['email'], 'invitation_code' => $invitation_code, 
                'user_details' => $user , 'inv_code_exp' => $inv_code_exp,
                'subject' => 'Stockgitter Invitation');
        
        // send email with activation code
        try {
            $this->send_email($options);
            $user->invitation_code = $invitation_code;
            $user->inv_code_exp = date("Y-m-d", strtotime($inv_code_exp));
            if($this->AppUsers->save($user)){
                $this->response->body(json_encode($user));
                $this->response->type('json');
                return $this->response;
            }  
        }
        catch  (\Exception $e) {
            $this->response->body(json_encode($e->getMessage()));
            $this->response->type('json');
            return $this->response;
        }
    }

    private function send_email($options){
        $template = $options['template'];
        $email = new \Cake\Mailer\Email();

        if (!empty($options['invitation_code'])) {
            $email->viewVars(array('invitation_code' => $options['invitation_code']));
        }
        if (!empty($options['user_details'])) {
            $email->viewVars(array('user_details' => $options['user_details']));
        }
        if (!empty($options['subject'])) {
            $email->viewVars(array('subject' => $options['subject']));
        }
        if (!empty($options['inv_code_exp'])) {
            $email->viewVars(array('inv_code_exp' => $options['inv_code_exp']));
        }
        try {
            $email->template($template, 'email_layout')
                ->emailFormat('html')
                ->to($options['to'])
                ->from('donotreply@leapinglogic.com')
                ->subject($options['subject'])
                ->send();
            return true;
        } 
        catch (\SocketException $exception) {
            return false;
        }
    }
}
