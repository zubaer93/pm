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

class StockController extends AppController
{

    public function all()
    {
        
    }

    /**
     * get Company info and set variable userId based on logged-in user
     *
     * @param $symbol string company symbol
     * @return mixed
     */
    public function import($id)
    {

        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $this->loadModel('Stocks');
            $this->loadModel('Companies');
            $path = $this->request->data['file']['tmp_name'];

            $options = [
                'length' => 0,
                'delimiter' => ',',
                'enclosure' => '"',
                'escape' => '\\',
                'headers' => true,
                'text' => false,
                'excel_bom' => false,
            ];

            $data = $this->Companies->importCsv($path, null, $options);

            $this->Stocks->importCSV($data, $id);


            $this->Flash->success(__('Successfully imported.'));

            $this->redirect(['_name' => 'all_company']);
        }
        $this->set(compact('id'));
        $this->set('_serialize', ['id']);
    }

    public function add()
    {
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $this->loadModel('Stocks');
            $check = $this->Stocks
                    ->find('all')
                    ->where(['company_id' => $this->request->data('company')])
                    ->where(['symbol' => $this->request->data('symbol')])
                    ->first();
            if (is_null($check)) {
                $result = $this->Stocks->addStock($this->request->data());

                if ($result) {
                    $this->Flash->success(__('Stock successfully added.'));
                } else {
                    $this->Flash->error(__('Stock was not added.'));
                }
            } else {
                $result = $this->Stocks->editStock($this->request->data(), $check->id);
                if ($result) {
                    $this->Flash->success(__('Stock successfully edited.'));
                } else {
                    $this->Flash->error(__('Stock was not edited.'));
                }
            }

            $this->redirect(['_name' => 'all_company']);
        }

        $this->loadModel('Companies');

        $all_companies = $this->Companies
                ->find('all', [
                    'fields' => ['id', 'name', 'symbol']
                ])
                ->toArray();
        $this->set(compact('all_companies'));
        $this->set('_serialize', ['all_companies']);
    }

    public function edit($id, $data = null)
    {
        if (empty($id)) {
            $this->redirect(['_name' => 'all_company_stock']);
        }

        $this->loadModel('Stocks');
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $result = $this->Stocks->editStock($this->request->data(), $id);

            if ($result) {
                $this->Flash->success(__('Stock successfully edited.'));
            } else {
                $this->Flash->error(__('Stock was not edited.'));
            }

            $this->redirect(['_name' => 'all_company_stock']);
        }

        $stock = $this->Stocks->get($id);
        $this->loadModel('Companies');
        $company = $this->Companies->get($stock->company_id);

        $this->set(compact('stock', 'company'));
        $this->set('_serialize', ['stock']);
    }

    public function delete($id)
    {
        $this->loadModel('Stocks');
        $result = $this->Stocks->deleteStock($id);
        if ($result) {
            $this->Flash->success(__('Stock successfully deleted.'));
        } else {
            $this->Flash->error(__('Stock was not deleted.'));
        }

        $this->redirect(['_name' => 'all_company_stock']);
    }

    public function info()
    {
        $id = $this->request->getQuery('id');
        $symbol = $this->request->getQuery('symbol');
        $this->loadModel('Stocks');
        $stock = $this->Stocks
                ->find('all')
                ->where(['company_id' => $id])
                ->where(['symbol'=>$symbol])
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

    public function ajaxStocksSearch()
    {
        $requestData = $this->request->getData();

        $obj = new \App\Model\DataTable\StockDataTable();
        $result = $obj->ajaxManageStocksSearch($requestData);

        echo $result;
        exit;
    }

}
