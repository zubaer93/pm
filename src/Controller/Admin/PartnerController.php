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
class PartnerController extends AppController
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
            $this->loadModel('Partners');
            $partner = $this->Partners;
            $requestData = $this->request->getData();

            $partnerSaved = $partner->addPartner($requestData);
            if (!$partnerSaved) {
                $this->Flash->error(__('The partner could not be saved'));

                return;
            } else {
                return $this->redirect(['_name' => 'partners_list']);
            }
        }
    }

    public function edit($id)
    {
        $this->loadModel('Partners');
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $entity = $this->Partners->editPartner($this->request->getData());

            if ($entity) {
                $this->Flash->success(__('Broker is Saved.'));
            } else {
                $this->Flash->error(__('Broker was not Saved.'));
            }

            return $this->redirect(['_name' => 'partners_list']);
        }

        $partner = $this->Partners->get($id);
        $this->set(compact('partner'));
        $this->set('_serialize', ['partner']);
    }

    public function delete($id)
    {
        $this->loadModel('Partners');
        $result = $this->Partners->deletePartner($id);

        if ($result) {
            $this->Flash->success(__('Partner successfully deleted.'));
        } else {
            $this->Flash->error(__('Partner was not deleted.'));
        }

        $this->redirect(['_name' => 'partners_list']);
    }

    public function disable($id)
    {
        $this->loadModel('Partners');
        $result = $this->Partners->disablePartners($id);

        if ($result) {
            $this->Flash->success(__('Partner is disabled.'));
        } else {
            $this->Flash->error(__('Partner was not disabled.'));
        }

        $this->redirect(['_name' => 'partners_list']);
    }

    public function enable($id)
    {
        $this->loadModel('Partners');
        $result = $this->Partners->enablePartners($id);

        if ($result) {
            $this->Flash->success(__('Partner is enabled.'));
        } else {
            $this->Flash->error(__('Partner was not enabled.'));
        }

        $this->redirect(['_name' => 'partners_list']);
    }

    public function ajaxManagePartnerSearch()
    {
        $requestData = $this->request->getQuery();

        $obj = new \App\Model\DataTable\PartnerDataTable();
        $result = $obj->ajaxManagePartnerSearch($requestData);

        echo $result;
        exit;
    }

}
