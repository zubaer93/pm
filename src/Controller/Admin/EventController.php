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
use Cake\I18n\Time;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class EventController extends AppController
{
    public function index()
    {
    }

    public function addEvent($id = null)
    {
        $data = $this->request->getData();
        if (isset($data)) {
            $this->loadModel('Events');
            $result = $this->Events->add($data);
            if ($result) {
                $this->Events->notify($result);
                $this->Flash->success('Event Created Successfully!');
                $this->redirect(['_name' => 'all_event']);
            } else {
                $this->Flash->error('Please again!');
            }
        }
    }

    public function all()
    {
    }

    public function ajaxEventSearch()
    {
        $requestData = $this->request->getQuery();
        $obj = new \App\Model\DataTable\EventDataTable();
        $result = $obj->ajaxEventSearch($requestData);

        echo $result;
        exit;
    }

    public function enable($id)
    {
        $this->loadModel('Events');
        $result = $this->Events->enable($id);

        if ($result) {
            $this->Flash->success(__('Event is enabled.'));
        } else {
            $this->Flash->error(__('Event was not enabled.'));
        }

        $this->redirect(['_name' => 'all_event']);
    }

    public function disable($id)
    {
        $this->loadModel('Events');
        $result = $this->Events->disable($id);

        if ($result) {
            $this->Flash->success(__('Event is disabled.'));
        } else {
            $this->Flash->error(__('Event was not disabled.'));
        }

        $this->redirect(['_name' => 'all_event']);
    }

    public function delete($id)
    {
        $this->loadModel('Events');
        $result = $this->Events->deleteEvent($id);
        if ($result) {
            $this->Flash->success(__('Event is deleted.'));
        } else {
            $this->Flash->error(__('Event was not deleted.'));
        }

        $this->redirect(['_name' => 'all_event']);
    }

    public function edit($id)
    {
        $this->loadModel('Events');

       if ($this->request->is('post') && !is_null($this->request->getData())) {

            $entity = $this->Events->edit($this->request->getData(),$id);

            if ($entity) {
                $this->Events->notify($entity);
                $this->Flash->success(__('Event successfully Edited.'));
            } else {
                $this->Flash->error(__('Broker was not Edited.'));
            }

            return $this->redirect(['_name' => 'all_event']);
        }

        $company = $this->Events->getCompany(self::JMD);
        $event = $this->Events->find()
            ->where(['Events.id' => $id])
            ->contain('Companies')
            ->first();

        $this->set(compact('event', 'company'));
    }

}
