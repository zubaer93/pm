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

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use App\Model\Service\Currency;
use Cake\Utility\Inflector;
use CakeDC\Users\Controller\Component\UsersAuthComponent;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PortfolioController extends AppController
{

    protected $_companies;
    protected $_currency;
    protected $_transactions;
    public $paginate = [
        'limit' => 1,
    ];

    const JMD = 'JMD';
    const USD = 'USD';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadModel('Companies');
        $this->loadModel('Transactions');
        $this->_companies = $this->Companies;
        $this->_transactions = $this->Transactions;
        $this->_currency = new Currency();
    }

    /**
     * index
     */
    public function index()
    {
        $checked = 0;
        $bool = true;
        $first_name = '';
        $Companies = TableRegistry::get('Companies');
        $client_name = $this->Auth->user('first_name');
        $all_companies = $Companies->find('list', array('fields' => array('name', 'id')))
                ->where(['Companies.enable' => 0])
                ->toArray();
        $Brokers = TableRegistry::get('Brokers');
        $result = $Brokers->find()
                ->where(['Brokers.enable' => 0])
                ->toArray();
        $all_brokers = [];
        foreach ($result as $val) {
            $all_brokers[$val['id']] = $val['first_name'] . ' ' . $val['last_name'];
        }
        $this->loadModel('SimulationSetting');
        $simulation = $this->SimulationSetting->find('all')
                ->contain('Brokers')
                ->where(['user_id' => $this->Auth->user('id')])
                ->first();
        $this->set(compact('checked', 'bool', 'first_name', 'all_companies', 'all_brokers', 'simulation', 'client_name'));

        $this->set('_serialize', ['checked']);
    }

    /**
     * savePreview
     */
    public function savePreview()
    {
        if (!$this->Auth->user()) {
            $this->redirect(['_name' => 'login']);
        }

        $saveTransaction = $this->_transactions->setTransactions($this->request->getData(), $this->Auth->user('id'));

        if ($saveTransaction) {
            $this->redirect(['_name' => 'transaction']);
        }
    }

    /* transaction  */

    public function transaction()
    {
        if (!$this->Auth->user()) {
            $this->redirect(['_name' => 'home']);
        }
        $user = $this->Auth->user();

        $inv_type = \App\Model\Service\Core::$investmentPreferences;
        $orderType = \App\Model\Service\Core::$orderType;
        $action = \App\Model\Service\Core::$action;

        $userId = $user['id'];
        $client_name = $user['first_name'];
        $this->loadModel('Transactions');

        $this->loadModel('Brokers');
        $query_brokers = $this->Brokers->find()
            ->where(['market' => $this->_getCurrentLanguage()]);

        $all_brokers = [];
        foreach ($query_brokers as $val) {
            $all_brokers[$val['id']] = $val['first_name'];
        }

        $all_transactions = $this->paginate($this->Transactions->getTransactions($userId, $this->_getCurrentLanguage(), $this->_getCurrentSubDomain()));

        $this->set(compact('all_transactions', 'all_brokers', 'current_language', 'inv_type', 'orderType', 'action', 'client_name'));
        $this->set('_serialize', ['all_transactions']);
    }

    /* transaction  */

    public function getBrokerList()
    {
        $market = $this->request->getQuery('market');

        $this->loadModel('Brokers');
        $query_brokers = $this->Brokers->find()
                ->where(['market' => $market]);

        $all_brokers = [];
        foreach ($query_brokers as $val) {
            $all_brokers[$val['id']] = $val['first_name'];
        }
        $response = [
            'status' => 'success',
            'data' => $all_brokers,
        ];
        $this->setJsonResponse($response);
        return $this->response;
    }

    /**
     * 
     * @return type
     */
    public function getCompanyPrice()
    {
        $requestData = $this->request->getQuery();
        $companies = $requestData['companies_id'];
        $last_price = 0;
        foreach ($companies as $id) {
            $symbol = $this->_companies->getCompanySymbol($id);
            $companyInfo = $this->_companies->getCompanyInfo($symbol, self::USD);
            $this->loadModel('Stocks');

            if (!is_null($companyInfo)) {
                $stockInfo = $this->Stocks->getStockInformation($companyInfo['symbol']);
            } else {
                $stockInfo = $this->Stocks->getStockInformationLocal($symbol, $id);
            }
            if (!is_null($stockInfo)) {
                $last_price = $stockInfo['info']['4. close'];
            }
        }
        $response = [
            'status' => 'success',
            'price' => $last_price,
            'message' => 'Successful!'
        ];

        $this->setJsonResponse($response);
        return $this->response;
    }

    /**
     * getCurrencyList 
     * @return type
     */
    public function getCurrencyList()
    {
        $requestData = $this->request->getQuery();
        $currency = $requestData['currency'];
        $list = array_flip($this->_currency::$currency);
        $array = [];
        foreach ($list as $key => $val) {

            if ($val != $currency) {
                $array[] = $currency . '-' . $val;
                $array[] = $val . '-' . $currency;
            }
        }

        list($current_price, $trade_price) = $this->getCurrencyPrice(reset($array));

        $response = [
            'status' => 'success',
            'list' => $array,
            'data' => array_splice($array, 0, 2, true),
            'current_price' => $current_price,
            'trade_price' => $trade_price,
            'message' => 'Successful!'
        ];
        $this->setJsonResponse($response);
        return $this->response;
    }

    public function getCurrencyRate()
    {

        $requestData = $this->request->getQuery();
        $currency = $requestData['currency'];
        $this->loadModel('Trader');

        list($current_price, $trade_price) = $this->Trader->__getTraderInfoFromCurrencyPortfolio($currency);

        $response = [
            'status' => 'success',
            'current_price' => $current_price,
            'trade_price' => $trade_price,
            'message' => 'Successful!'
        ];
        $this->setJsonResponse($response);
        return $this->response;
    }

    public function getCompany()
    {
        $this->loadModel('Companies');
        $this->loadModel('Brokers');
        $requestMarket = $this->request->getQuery('market');
        $search = $this->request->getQuery('search');
        $allBrokers = $this->Brokers->find()
                ->where(['Brokers.enable' => 0])
                ->where(['Brokers.market' => $requestMarket])
                ->where([
                    'OR' => [
                        ['Brokers.first_name LIKE' => '%' . $search . '%'],
                        ['Brokers.last_name LIKE' => '%' . $search . '%'],
                    ]
                ])
                ->toArray();
        $all_brokers = [];
        foreach ($allBrokers as $val) {
            $all_brokers[$val['id']] = $val['first_name'];
        }

        $allCompany = $this->Companies->getSearchCompanyWithLang($requestMarket, $search);
        $response = [
            'status' => 'success',
            'allBrokers' => $all_brokers,
            'allCompony' => $allCompany,
            'message' => 'Successful!'
        ];

        $this->setJsonResponse($response);
        return $this->response;
    }

    protected function getCurrencyPrice($currency)
    {
        $this->loadModel('Trader');

        $exchangeInfo = $this->Trader->__getTraderInfoFromCurrencyPortfolio($currency);

        return $exchangeInfo;
    }

    /**
     * 
     * @return type
     */
    public function getBrokerFee()
    {
        $this->loadModel('Brokers');
        $requestData = $this->request->getQuery();
        $id = $requestData['broker'];
        $market = $requestData['market'];
        if (empty($market)) {
            $market = $this->_getCurrentLanguage();
        }
        $brokerInfo = $this->Brokers->__getBrokersInfo($id, $market);
        if (!is_null($brokerInfo)) {
            $response = [
                'status' => 'success',
                'data' => $brokerInfo,
                'message' => 'Successful!'
            ];
            $this->response->statusCode(200);
        } else {
            $response = [
                'status' => 'error',
                'data' => null,
                'message' => 'Error!'
            ];
            $this->response->statusCode(424);
        }
        $this->setJsonResponse($response);
        return $this->response;
    }

    protected function setJsonResponse($response)
    {
        $response = json_encode($response);
        $this->response->type('application/json');
        $this->response->body($response);
    }

    /* simulations function */

    public function simulations()
    {

        $this->paginate = [
            'limit' => 4,
        ];
        if (!$this->Auth->user()) {
            $this->redirect(['_name' => 'home']);
        }
        $this->loadModel('Watchlist');
        $this->loadModel('SimulationSetting');
        $this->loadModel('Stocks');
        $this->loadModel('Brokers');
        $this->loadModel('Simulations');
        $userId = $this->Auth->user('id');
        $all_simulation = $this->paginate($this->Simulations->getSimulation($userId, $this->_getCurrentLanguage(), $this->_getCurrentSubDomain()));

        $simulation_setting = $this->SimulationSetting->find('all')
            ->where(['SimulationSetting.user_id' => $userId])
            ->contain(['Brokers' => function ($q) {
                    return $q->autoFields(false);
                }])
            ->first();

        if (is_null($simulation_setting)) {
            if ($this->_getCurrentLanguage() == self::JMD) {
                $broker = $this->Brokers->find()
                        ->where(['Brokers.market' => self::JMD])
                        ->first();
            } else {
                $broker = $this->Brokers->find()
                        ->where(['Brokers.market' => self::USD])
                        ->first();
            }
            $simulation_setting = (object) ['quantity' => 100, 'broker' => $broker];
        };

        $getSimulations = $this->Simulations->getSimulation($userId, $this->_getCurrentLanguage(), $this->_getCurrentSubDomain())->all();
        $totalPrice = 0;
        $totalInvAmount = 0;
        foreach ($getSimulations as $val) {
            $totalPrice += self::setSimulations($val->company->symbol, $val->company->id, $this->_getCurrentLanguage(), $val->price, $simulation_setting->quantity);
            $totalInvAmount += $simulation_setting->quantity * $val->price;
        }

        $this->set(compact('simulation_setting', 'all_simulation', 'userId', 'totalPrice', 'totalInvAmount'));
        $this->set('_serialize', ['all_simulation']);
    }

    public function simulationsChart()
    {

        $id = $this->request->getQuery('id');
        $symbol = $this->request->getQuery('symbol');
        $companyId = $this->request->getQuery('compony_id');
        $quantity = $this->request->getQuery('quantity');
        $date = $this->request->getQuery('date');
        $price = $this->request->getQuery('price');
        $gainLoss = $this->request->getQuery('gainLoss');
        $total = $this->request->getQuery('total');
        $fees = $this->request->getQuery('fees');
        $broker = $this->request->getQuery('broker');
        $inv_amount = $quantity * $price;

        $this->loadModel('Stocks');
        if ($this->_getCurrentLanguage() == self::JMD) {
            $paramsChart = $this->Stocks->getStockSimulationChart($symbol, $companyId, $date, $quantity, $price);
            $stockInfo = $this->Stocks->getStockInformationLocal($symbol, $companyId);
        } else {
            $paramsChart = $this->Stocks->getStockSimulationUSDChart($symbol, $date, $price, $quantity);
            $stockInfo = $this->Stocks->getStockInformation($symbol);
        }

        $paramsChart = json_encode($paramsChart, true);

        $this->set(compact('id', 'paramsChart', 'stockInfo', 'current_language', 'quantity', 'gainLoss', 'total', 'inv_amount', 'fees', 'broker'));
        return $this->render('simulations_chart', 'ajax');
    }

    public static function setSimulations($symbol, $company_id, $current_language, $price, $count)
    {

        $stockInfo = TableRegistry::get('Stocks');

        if ($current_language == self::JMD) {
            $stockInfo = $stockInfo->getStockInformationLocal($symbol, $company_id);
            $stock = $stockInfo['info']['1. open'] + $stockInfo['info']['6.price_change'];
        } else {
            $stockInfo = $stockInfo->getStockInformation($symbol);
            $stock = $stockInfo['info']['1. open'];
        }
        $price = $stock - $price;
        return $price * $count;
    }

    public static function getSimulationsStockCurrentPrice($symbol, $company_id, $current_language, $count)
    {

        $stockInfo = TableRegistry::get('Stocks');

        if ($current_language == self::JMD) {
            $stockInfo = $stockInfo->getStockInformationLocal($symbol, $company_id);
            $stock = $stockInfo['info']['1. open'] + $stockInfo['info']['6.price_change'];
        } else {
            $stockInfo = $stockInfo->getStockInformation($symbol);
            $stock = $stockInfo['info']['1. open'];
        }

        return $stock;
    }

    public static function setCompanyPrice($price, $simulation_setting, $gainLoss, $inv_amount)
    {

        $percent = $simulation_setting->broker->percent;

        $fee = (float) $simulation_setting->broker->fee;
        $exchange_fee = (float) $simulation_setting->broker->exchange_fee;
        $trade_fee = (float) $simulation_setting->broker->trade_fee;

        $broker = $simulation_setting->broker->first_name;
        if ($percent) {
            $fee = ($price * $fee) / 100;
        }
        $fees = $exchange_fee + $trade_fee + $fee;
        $price = ( $gainLoss + $fees + $inv_amount);
        $all = ( $fees + $inv_amount);
        return ['fees' => $fees, 'total' => $price, 'broker' => $broker, 'inv_amount' => $all];
    }

    public function simulationDelete()
    {
        $this->loadModel('Simulations');
        $requestData = $this->request->getQuery('data');
        $bool = true;
        $array = [];
        if (!$this->Auth->user()) {
            $bool = false;
        } else {
            $userId = $this->Auth->user('id');
            $simulation = $this->Simulations->find()
                    ->where(['user_id' => $userId])
                    ->where(function ($simulation, $q) use ($requestData)
                    {
                        return $simulation->in('id', $requestData);
                    })
                    ->toArray();
            foreach ($simulation as $key => $data) {
                if (!$this->Simulations->delete($data)) {
                    $bool = false;
                } else {
                    $array[] = $data->id;
                }
            }
            if ($bool) {
                $response = [
                    'status' => 'success',
                    'data' => $array,
                    'message' => 'Successful!'
                ];
                $this->response->statusCode(200);
                $this->setJsonResponse($response);
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'error!'
                ];
                $this->response->statusCode(500);
                $this->setJsonResponse($response);
            }
            return $this->response;
        }
    }

    public function transactionCancel()
    {
        $id = $this->request->getQuery('id');
        $this->loadModel('Transactions');
        $cancel = $this->Transactions->cancelTransaction($id);
        if ($cancel) {
            $response = [
                'status' => 'success',
            ];
            $this->setJsonResponse($response);
            return $this->response;
        }
    }

    public function portfolioEdit($id)
    {
        $this->loadModel('Transactions');
        if ($this->request->is('post')) {
            $data = $this->request->data();
            $update = $this->Transactions->updateTransaction($id, $data);
            if ($update) {
                $this->Flash->success('Updated Trade summary!');
                $this->redirect(['_name' => 'transaction']);
            } else {
                $this->Flash->error('Please again!');
            }
        }
        if (!$this->Auth->user()) {
            $this->redirect(['_name' => 'home']);
        }
        $this->loadModel('Brokers');
        $this->loadModel('Transactions');
        $userId = $this->Auth->user('id');
        $data = $this->Transactions->find()
                ->contain('Companies')
                ->contain('BrokersList')
                ->where(['Transactions.id' => $id])
                ->where(['Transactions.user_id' => $userId])
                ->first();

        if (is_null($data)) {
            $this->redirect(['_name' => 'home']);
        }

        $this->set(compact('data', 'all_companies', 'all_brokers'));
        $this->render('transaction_edit');
    }

    public function order()
    {
        
    }

    public function orderDataTable()
    {
        $userId = $this->Auth->user('id');
        $requestData = $this->request->getQuery();
        $obj = new \App\Model\DataTable\OrderDataTable();
        $result = $obj->ajaxOrderSearch($requestData, $userId);
        echo $result;
        exit;
    }

    public function placeOrder($id)
    {
        $this->loadModel('Transactions');
        $orderUpdate = $this->Transactions->order($id);
        if ($orderUpdate) {
            $this->Flash->success('Order placed successfully');
        } else {
            $this->Flash->error('Order was not placed successfully');
        }

        $this->redirect(['_name' => 'order']);
    }
}
