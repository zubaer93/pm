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

class BuySellBrokerController extends AppController
{

    public function all()
    {
        $this->loadModel('BuySellBroker');
        $brokers_details = $this->BuySellBroker->find('all', [
                    'fields' => ['company_id' => 'BuySellBroker.company_id',
                        'broker_id' => 'max(BuySellBroker.broker_id)',
                        'id' => 'max(BuySellBroker.id)',
                        'created_at' => 'max(BuySellBroker.created_at)',
                        'symbol' => 'max(Companies.symbol)'
                    ],
                    'group' => 'BuySellBroker.company_id',
                    'contain' => array('Companies')
                        ]
                )->toArray();
        
        $this->loadModel('Brokers');
        $all_brokers = $this->Brokers->find();

        $this->set(compact('brokers_details', 'all_brokers'));
        $this->set('_serialize', ['brokers_details', 'all_brokers']);
    }

    public function add()
    {
        if ($this->request->is('post') && !is_null($this->request->getData())) {
            $this->loadModel('BuySellBroker');
            $result = $this->BuySellBroker->addBuySellBroker($this->request->data());

            if ($result) {
                $this->Flash->success(__('Buy Sell successfully added.'));
            } else {
                $this->Flash->error(__('Buy Sell was not added.'));
            }

            $this->redirect(['_name' => 'buy_sell_broker']);
        }


        $this->loadModel('Companies');
        $this->loadModel('Brokers');
        $language = 'JMD';
        $all_companies = $this->Companies->find('list', array('fields' => array('name', 'id')))
                ->contain(['Exchanges' => function ($q) use ($language)
                    {
                        return $q->autoFields(false)
                                ->contain(['Countries' => function ($q) use ($language)
                                    {
                                        return $q->autoFields(false)
                                                ->where(['Countries.market' => $language]);
                                    }]);
                    }])
                ->where(['Companies.enable !=' => 1])
                ->toArray();
        $query_brokers = $this->Brokers->find()
                ->toArray();
        $all_brokers = [];
        foreach ($query_brokers as $val) {
            $all_brokers[$val['id']] = $val['first_name'];
        }

        $this->set(compact('all_companies', 'all_brokers'));
        $this->set('_serialize', ['all_companies', 'all_brokers']);
    }

    public function edit($id, $data = null)
    {
        if (empty($id)) {
            $this->redirect(['_name' => 'buy_sell_broker']);
        }

        $this->loadModel('BuySellBroker');
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $result = $this->BuySellBroker->editBuySellBroker($this->request->data(), $id);

            if ($result) {
                $this->Flash->success(__('Broker Details successfully edited.'));
            } else {
                $this->Flash->error(__('Broker Details was not edited.'));
            }

            $this->redirect(['_name' => 'buy_sell_broker']);
        }

        $broker = $this->BuySellBroker->find()
                ->contain(['Companies' => function ($q)
                    {
                        return $q->autoFields(false);
                    }])
                ->contain(['Brokers' => function ($q)
                    {
                        return $q->autoFields(false);
                    }])
                ->where(['BuySellBroker.id' => $id])
                ->first();

        $this->loadModel('Companies');
        $this->loadModel('Brokers');
        $language = 'JMD';
        $all_companies = $this->Companies->find('list', array('fields' => array('name', 'id')))
                ->contain(['Exchanges' => function ($q) use ($language)
                    {
                        return $q->autoFields(false)
                                ->contain(['Countries' => function ($q) use ($language)
                                    {
                                        return $q->autoFields(false)
                                                ->where(['Countries.market' => $language]);
                                    }]);
                    }])
                ->where(['Companies.enable !=' => 1]);
        $query_brokers = $this->Brokers->find()
                ;
        $broker_details = $this->BuySellBroker->find()
                ->where(['company_id' => $broker['company_id']])
                ->toList();
        
        $all_brokers = [];
        foreach ($query_brokers as $val) {
            $all_brokers[$val['id']] = $val['first_name'];
        }

        $this->set(compact('broker', 'all_companies', 'all_brokers','broker_details'));
        $this->set('_serialize', ['broker']);
    }

    public function delete($id)
    {
        $this->loadModel('BuySellBroker');
        $company_id = $this->BuySellBroker->get($id);
        $result = $this->BuySellBroker->deleteBuySellBrokersByCompanyId($company_id['company_id']);
        if ($result) {
            $this->Flash->success(__('Broker Details successfully deleted.'));
        } else {
            $this->Flash->error(__('Broker Details was not deleted.'));
        }

        $this->redirect(['_name' => 'buy_sell_broker']);
    }

    protected function setJsonResponse($response)
    {
        $response = json_encode($response);
        $this->response->type('application/json');
        $this->response->body($response);
    }

    public function ajaxManageBuySellBrokerSearch()
    {
        $requestData = $this->request->getQuery();
        $obj = new \App\Model\DataTable\BuySellBrokerDataTable();
        $result = $obj->ajaxManageBuySellBrokerSearch($requestData);
        echo $result;
        exit;
    }

}
