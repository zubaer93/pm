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
class PagesManagementController extends AppController
{
    /**
     * all method this method will list all IPO markets.
     *
     * @return void
     */
    public function all()
    {
        $this->loadModel('Pages');
        $data = $this->Pages
                ->find();

        $pages = $this->paginate($data);

        $this->set(compact('pages'));
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

            $this->loadModel('Pages');
            $result = $this->Pages->setPage($getData);

            if ($result) {
                $this->Flash->success(__('Page successfully added.'));
            } else {
                $this->Flash->error(__('Page was not added.'));
            }
            $this->redirect(['_name' => 'all_pages']);
        }
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
            $this->loadModel('Pages');
            if ($this->request->is('post') && !is_null($this->request->getData())) {
                $result = $this->Pages->editPages($this->request->getData());
                if ($result) {
                    $this->Flash->success(__('Page successfully edited.'));
                } else {
                    $this->Flash->error(__('Page was not edited.'));
                }

                $this->redirect(['_name' => 'all_pages']);
            }

            $page = $this->Pages->get($id);

            $this->set(compact('page'));
            $this->set('_serialize', ['page']);
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
            $this->loadModel('Pages');
            $result = $this->Pages->deletePage($id);
            if ($result) {
                $this->Flash->success(__('Page successfully deleted.'));
            } else {
                $this->Flash->error(__('Page was not deleted.'));
            }
            $this->redirect(['_name' => 'all_pages']);
        } else {
            $this->redirect(['_name' => 'home']);
        }
    }

    public function disable($id)
    {

        $this->loadModel('Pages');
        $result = $this->Pages->disablePage($id);

        if ($result) {
            $this->Flash->success(__('Page is disabled.'));
        } else {
            $this->Flash->error(__('Page was not disabled.'));
        }

        $this->redirect(['_name' => 'all_pages']);
    }

    public function enable($id)
    {

        $this->loadModel('Pages');
        $result = $this->Pages->enablePage($id);

        if ($result) {
            $this->Flash->success(__('Page is enabled.'));
        } else {
            $this->Flash->error(__('Page was not enabled.'));
        }

        $this->redirect(['_name' => 'all_pages']);
    }

}
