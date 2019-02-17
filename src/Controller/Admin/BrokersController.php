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
class BrokersController extends AppController
{

    const JMD = 'JMD';

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
            $broker = $this->Brokers;
            $requestData = $this->request->getData();

            $brokerSaved = $broker->addBroker($requestData);
            if (!$brokerSaved) {
                $this->Flash->error(__('The broker could not be saved'));

                return;
            } else {
                return $this->redirect(['_name' => 'brokers_list']);
            }
        }
    }

    public function edit($id)
    {
        if ($this->request->is('post') && !is_null($this->request->getData())) {
            $tableAlias = $this->Brokers->alias();
            $entity = $this->Brokers->get($id, [
                'contain' => []
            ]);
            $this->set($tableAlias, $entity);
            $this->set('tableAlias', $tableAlias);
            $this->set('_serialize', [$tableAlias, 'tableAlias']);

            $entity = $this->Brokers->patchEntity($entity, $this->request->getData());
            $entity->percent = ($this->request->getData('market') == self::JMD) ? 1 : 0;
            
            if ($this->Brokers->save($entity)) {
                $this->Flash->success(__('Broker is Saved.'));

                return $this->redirect(['_name' => 'brokers_list']);
            }
            $this->Flash->error(__('Broker was not Saved.'));
        }

        $broker = $this->Brokers->get($id);
        $this->set(compact('broker'));
        $this->set('_serialize', ['broker']);
    }

    public function delete($id)
    {
        $result = $this->Brokers->deleteBroker($id);

        if ($result) {
            $this->Flash->success(__('Broker successfully deleted.'));
        } else {
            $this->Flash->error(__('Broker was not deleted.'));
        }

        $this->redirect(['_name' => 'brokers_list']);
    }

    public function disable($id)
    {

        $result = $this->Brokers->disableBrokers($id);

        if ($result) {
            $this->Flash->success(__('Broker is disabled.'));
        } else {
            $this->Flash->error(__('Broker was not disabled.'));
        }

        $this->redirect(['_name' => 'brokers_list']);
    }

    public function enable($id)
    {

        $result = $this->Brokers->enableBrokers($id);

        if ($result) {
            $this->Flash->success(__('Broker is enabled.'));
        } else {
            $this->Flash->error(__('Broker was not enabled.'));
        }

        $this->redirect(['_name' => 'brokers_list']);
    }

    public function ajaxManageBrokersSearch()
    {
        $requestData = $this->request->getQuery();

        $obj = new \App\Model\DataTable\BrokersDataTable();
        $result = $obj->ajaxManageBrokersSearch($requestData);

        echo $result;
        exit;
    }

}
