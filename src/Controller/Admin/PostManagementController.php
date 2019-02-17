<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PostManagementController extends AppController
{

    public function initialize()
    {
        $this->loadComponent('Flash');
    }

    /**
     * all method this method will list all IPO markets.
     *
     * @return void
     */
    public function all()
    {
        
    }

    /**
     * add method this method will add an Post.
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $getData = $this->request->getData();

            $this->loadModel('Messages');
            $result = $this->Messages->setMessageWithAdmin($getData);

            if ($result) {
                $this->Flash->success(__('Message successfully added.'));
            } else {
                $this->Flash->error(__('Message was not added.'));
            }
            $this->redirect(['_name' => 'all_posts']);
        }

        $this->loadModel('Companies');
        $all_companies = $this->Companies
                        ->find('list')->toArray();

        $this->loadModel('AppUsers');
        $all_users = $this->AppUsers
                        ->find('list')->toArray();
        $this->loadModel('Countries');
        $all_countries = $this->Countries
                        ->find('list')->toArray();

        $this->set(compact('all_companies', 'all_users', 'all_countries'));
    }

    /**
     * edit method this method will edit an Post.
     *
     * @param integer $id
     * @return void
     */
    public function edit($id)
    {
        if (!is_null($id)) {
            $this->loadModel('Messages');
            if ($this->request->is('post') && !is_null($this->request->getData())) {
                $result = $this->Messages->editMessageWithAdmin($this->request->getData());
                if ($result) {
                    $this->Flash->success(__('Message successfully edited.'));
                } else {
                    $this->Flash->error(__('Message was not edited.'));
                }

                $this->redirect(['_name' => 'all_posts']);
            }

            $post = $this->Messages
                    ->find()
                    ->where(['Messages.id' => $id])
                    ->contain('AppUsers')
                    ->contain('Countries')
                    ->first();
            $this->loadModel('Companies');

            $all_companies = $this->Companies
                            ->find('list')->toArray();
            $this->loadModel('Countries');
            $all_countries = $this->Countries
                            ->find('list')->toArray();
            $this->set(compact('post', 'all_companies', 'all_countries'));
            $this->set('_serialize', ['post']);
        } else {
            $this->redirect(['_name' => 'home']);
        }
    }

    /**
     * delete method this method will delete an Post.
     *
     * @param integer $id
     * @return void
     */
    public function delete($id)
    {
        if (!is_null($id)) {
            $this->loadModel('Messages');
            $result = $this->Messages->deletMessageWithAdmin($id);
            if ($result) {
                $this->Flash->success(__('Message successfully deleted.'));
            } else {
                $this->Flash->error(__('Message was not deleted.'));
            }
            $this->redirect(['_name' => 'all_posts']);
        } else {
            $this->redirect(['_name' => 'home']);
        }
    }

    public function ajaxManagePostSearch()
    {
        $requestData = $this->request->getData();

        $obj = new \App\Model\DataTable\PostDataTable();
        $result = $obj->ajaxManagePostSearch($requestData);

        echo $result;
        exit;
    }

}
