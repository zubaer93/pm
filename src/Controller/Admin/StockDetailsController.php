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

class StockDetailsController extends AppController
{

    public function all()
    {
        
    }

    public function add()
    {
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $this->loadModel('StocksDetails');
            $result = $this->StocksDetails->addStocksDetails($this->request->data());

            if ($result) {
                $this->Flash->success(__('Stock Details successfully added.'));
            } else {
                $this->Flash->error(__('Stock Details was not added.'));
            }

            $this->redirect(['_name' => 'stock_details']);
        }

        $this->loadModel('Companies');
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

        $this->set(compact('all_companies'));
        $this->set('_serialize', ['all_companies']);
    }

    public function edit($id, $data = null)
    {
        if (empty($id)) {
            $this->redirect(['_name' => 'stock_details']);
        }

        $this->loadModel('StocksDetails');
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $result = $this->StocksDetails->editStock($this->request->data(), $id);

            if ($result) {
                $this->Flash->success(__('Stock details successfully edited.'));
            } else {
                $this->Flash->error(__('Stock details was not edited.'));
            }

            $this->redirect(['_name' => 'stock_details']);
        }

        $stock = $this->StocksDetails->get($id);

        $this->loadModel('Companies');
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

        $this->set(compact('stock', 'all_companies'));
        $this->set('_serialize', ['stock']);
    }

    public function delete($id)
    {
        $this->loadModel('StocksDetails');
        $result = $this->StocksDetails->deleteStocksDetails($id);
        if ($result) {
            $this->Flash->success(__('Stock Details successfully deleted.'));
        } else {
            $this->Flash->error(__('Stock Details was not deleted.'));
        }

        $this->redirect(['_name' => 'stock_details']);
    }

    public function info()
    {
        $id = $this->request->getQuery('id');
        $symbol = $this->request->getQuery('symbol');
        $this->loadModel('Stocks');
        $stock = $this->Stocks
                ->find('all')
                ->where(['company_id' => $id])
                ->where(['symbol' => $symbol])
                ->first();
        $response = [
            'status' => 'success',
            'data' => $stock,
            'message' => 'successful!'
        ];

        $this->response->statusCode(200);
        $this->setJsonResponse($response);
        return $this->response;
    }

    protected function setJsonResponse($response)
    {
        $response = json_encode($response);
        $this->response->type('application/json');
        $this->response->body($response);
    }

    public function ajaxManageStockSearch()
    {
        $requestData = $this->request->getData();
        $obj = new \App\Model\DataTable\StockDetailsDataTable();
        $result = $obj->ajaxManageStockDetailsSearch($requestData);
        echo $result;
        exit;
    }

}
