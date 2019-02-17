<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;

class AffiliatesController extends AppController
{
    /**
     * get Company info and set variable userId based on logged-in user
     *
     * @param $symbol string company symbol
     * @return mixed
     */
    public function index()
    {

    }

    /**
     * add method it will add a new Company.
     *
     * @return void
     */
    public function add()
    {
        $affiliate = $this->Affiliates->newEntity();
        if ($this->request->is('post')) {
            $affiliate = $this->Affiliates->patchEntity($affiliate, $this->request->getData());
            if ($this->Affiliates->save($affiliate)) {
                $this->Flash->success(__('Affiliate company successfully added.'));
                $this->redirect(['_name' => 'all_affiliates']);
            } else {
                $this->Flash->error(__('Affiliate company was not added.'));
            }
        }

        $this->set(compact('affiliate'));
        $this->set('_serialize', ['affiliate']);
    }

    /**
     * edit method it will edit an existing Company.
     *
     * @param int $id Company id
     * @return void
     */
    public function edit($id)
    {
        $affiliate = $this->Affiliates->get($id);
        if ($this->request->is('post') || $this->request->is('put')) {
            $affiliate = $this->Affiliates->patchEntity($affiliate, $this->request->getData());
            if ($this->Affiliates->save($affiliate)) {
                $this->Flash->success(__('Affiliate company successfully edited.'));
                $this->redirect(['_name' => 'all_affiliates']);
            } else {
                $this->Flash->error(__('Affiliate company was not edited.'));
            }
        }

        if (!empty($affiliate->showDate)) {
            $affiliate->date_of_incorporation = $affiliate->showDate;
        }
        $this->set(compact('affiliate'));
        $this->set('_serialize', ['affiliate']);
    }

    /**
     * delete method it will delete a Company.
     *
     * @param int $id Company id
     * @return void
     */
    public function delete($id)
    {
        $affiliate = $this->Affiliates->get($id);

        if ($this->Affiliates->delete($affiliate)) {
            $this->Flash->success(__('Affiliate company successfully deleted.'));
        } else {
            $this->Flash->error(__('Affiliate company was not deleted.'));
        }

        $this->redirect(['_name' => 'all_affiliates']);
    }

    public function ajaxManageAffiliatesSearch()
    {
        $requestData = $this->request->getData();

        $obj = new \App\Model\DataTable\AffiliatesDataTable();
        $result = $obj->ajaxManageAffiliatesSearch($requestData);

        echo $result;
        exit;
    }
}
