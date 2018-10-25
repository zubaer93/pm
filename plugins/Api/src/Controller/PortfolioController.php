<?php

namespace Api\Controller;


use Cake\Network\Exception\NotFoundException;

use Cake\ORM\TableRegistry;
use Api\Model\Service\Currency;
use Cake\Utility\Hash;


class PortfolioController extends AppController
{
    protected $_companies;
    protected $_currency;
    protected $_transactions;

    const JMD = 'JMD';
    const USD = 'USD';


    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadModel('Api.Companies');
        $this->loadModel('Api.Transactions');
        $this->_companies = $this->Companies;
        $this->_transactions = $this->Transactions;
        $this->_currency = new Currency();

    }

    public function transactions()
    {
        $this->add_model(array('Api.AppUsers'));
        //$user = $this->Users->find()->where(['api_token' => $this->jwtToken])->first();
        $user = $this->AppUsers->find()->where(['AppUsers.id' => $this->jwtPayload->id])->first();
        if ($user) {
            $inv_type = \Api\Model\Service\Core::$investmentPreferences;
            $orderType = \Api\Model\Service\Core::$orderType;
            $action = \Api\Model\Service\Core::$action;

            $userId = $user['id'];
            $client_name = $user['first_name'];
            $this->loadModel('Api.Transactions');

            $this->loadModel('Api.Brokers');
            $query_brokers = $this->Brokers->find()
                ->where(['market' => $this->currentLanguage]);

            $all_brokers = [];
            foreach ($query_brokers as $val) {
                $all_brokers[$val['id']] = $val['first_name'];
            }

            $all_transactions = $this->paginate($this->Transactions->getTransactions($userId, $this->currentLanguage, $this->_getCurrentSubDomain()));

            $data['all_transactions'] = $all_transactions;
            $data['all_brokers'] = $all_brokers;
            $data['inv_type'] = $inv_type;
            $data['orderType'] = $orderType;
            $data['action'] = $action;
            $data['client_name'] = $client_name;

            if (!empty($data)) {
                $this->apiResponse['data'] = $data;
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Transactions'];
            } else {
                $this->apiResponse['data'] = [];
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Transactions'];
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'You are not signed in';
        }
    }
    //get list of all matched companies with stock price
    public function getCompany()
    {
        $this->loadModel('Api.Companies');
        $requestMarket = $this->request->getQuery('market');
        $search = $this->request->getQuery('search');

        $allCompany = $this->Companies->getSearchCompany($requestMarket, $search);
        if (!empty($allCompany)) {
            $this->apiResponse['data'] = $allCompany;
        } else {
            $this->apiResponse['data'] = [];
        }
    }
    /**
     * 
     * @return type
     */
    public function getCompanyPrice()
    {
        $company_id = $this->request->getQuery('company_id');
        $this->loadModel('Api.Companies');
        $last_price = 0;
        $symbol = $this->Companies->getCompanySymbol($company_id);
        $companyInfo = $this->Companies->getCompanyInfo($symbol, self::USD);
        $this->loadModel('Api.Stocks');
        if (!is_null($companyInfo)) {
            $stockInfo = $this->Stocks->getStockInformation($companyInfo['symbol']);
        } else {
            $stockInfo = $this->Stocks->getStockInformationLocal($symbol, $company_id);
        }
        if (!is_null($stockInfo)) {
            $last_price = $stockInfo['info']['close'];
        }
        if (!empty($last_price)) {
            $this->apiResponse['price'] = $last_price;
        } else {
            $this->apiResponse['price'] = [];
        }
    }
    public function getBrokerList()
    {
        $market = $this->currentLanguage;

        $this->loadModel('Api.Brokers');
        $query_brokers = $this->Brokers->find()
            ->where(['market' => $market]);

        $all_brokers = [];
        foreach ($query_brokers as $val) {
            $all_brokers[] = $val;
        }
        // $response = [
        //     'status' => 'success',
        //     'data' => $all_brokers,
        // ];
        if (!empty($all_brokers)) {
            $this->apiResponse['data'] = $all_brokers;
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Brokers'];
        } else {
            $this->apiResponse['data'] = [];
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Brokers'];
        }
    }

    /**
     * getCurrencyList
     * @return type
     */
    public function getCurrencyList()
    {
        $requestData = $this->request->getQuery();
        $currency = $this->currentLanguage;

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
            'list' => $array,
            'data' => array_splice($array, 0, 2, true),
            'current_price' => $current_price,
            'trade_price' => $trade_price,
        ];
        if (!empty($response)) {
            $this->apiResponse['data'] = $response;
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Trader'];
        } else {
            $this->apiResponse['data'] = [];
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Trader'];
        }
    }

    protected function getCurrencyPrice($currency)
    {
        $this->loadModel('Api.Trader');

        $exchangeInfo = $this->Trader->__getTraderInfoFromCurrencyPortfolio($currency);

        return $exchangeInfo;
    }

    /**
     *
     * @return type
     */
    public function getBrokerFee($broker_id)
    {
        if (!empty($broker_id)) {
            $this->loadModel('Api.Brokers');
            $requestData = $this->request->getQuery();
            $market = $this->currentLanguage;

            $brokerInfo = $this->Brokers->__getBrokersInfo($broker_id, $market);
            if (!empty($brokerInfo)) {
                $this->apiResponse['data'] = $brokerInfo;
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Brokers'];
            } else {
                $this->apiResponse['data'] = [];
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Brokers'];
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'Insert broker id';
        }
    }

    /**
     * save order Preview
     */
    public function savePreview()
    {
        $this->loadModel('Api.Transactions');
        $this->loadModel('Api.Companies');

        $data = $this->request->getData();
        $companyInfo = $this->Companies->find()->where(['Companies.id' => $data['company']])->first();
        if (isset($data)) {
            $transaction = $this->Transactions->newEntity();
            $transaction->user_id = $this->jwtPayload->id;
            $transaction->price = $data['company_price'];
            $transaction->fees = $data['total_fee'];
            $transaction->client_name = $data['client_name'];
            $transaction->investment_amount = $data['investment_amount'];
            $transaction->order_type = $data['order_type'];
            $transaction->market = $data['select_market'];
            $transaction->action = $data['action'];
            $transaction->investment_preferences = $data['investment_preferences'];
            $transaction->company_id = $data['company'];
            $transaction->quantity_to_buy = $data['quantity_to_buy'];
            $transaction->broker = $data['broker'];
            $transaction->total = $data['total'];
            $transaction->status = 0;
            if ($data['order_type'] === '2') {
                $transaction->limit_price = $data['limit_price'];
            }
            $save = $this->Transactions->save($transaction);
            $time = $this->Transactions->find()->where(['Transactions.id' => $save['id']])->first();
            $showData = $save;
            $showData['company_name'] = $companyInfo['name'];
            $showData['company_symbol'] = $companyInfo['symbol'];
            $showData['created_at'] = $time['created_at'];
            if ($data) {
                $this->apiResponse['data'] = $showData;
            } else {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'error';
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'insert data';
        }
    }

    public function placeOrder()
    {
        $id = $this->request->getData('id');
        if (!empty($id)) {
            $this->loadModel('Api.Transactions');
            $orderUpdate = $this->Transactions->order($id);
            if ($orderUpdate) {
                $this->apiResponse['data'] = $orderUpdate;
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Transactions'];
            } else {
                $this->apiResponse['data'] = [];
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Transactions'];
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'Insert id';
        }
    }

    public function transactionCancel($transaction_id)
    {
        if (!empty($transaction_id)) {
            $this->loadModel('Api.Transactions');
            $cancel = $this->Transactions->cancelTransaction($transaction_id);
            if ($cancel) {
                $this->apiResponse['data'] = $cancel;
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Transactions'];
            } else {
                $this->apiResponse['data'] = [];
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Transactions'];
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'Insert id';
        }
        // if ($cancel) {
        //     $response = [
        //         'status' => 'success',
        //     ];
        //     $this->setJsonResponse($response);
        //     return $this->response;
        // }

    }

    public function order()
    {

    }

    //for datatable api
    public function orderDataTable()
    {
        $this->add_model(array('Api.Users'));
        $user = $this->Users->find()->where(['api_token' => $this->jwtToken])->first();
        $userId = $user['id'];

        $requestData = $this->request->getQuery();
        $obj = new \Api\Model\DataTable\OrderDataTable();
        $result = $obj->ajaxOrderSearch($requestData, $userId);
        if (!empty($result)) {
            $this->apiResponse = $result;
        } else {
            $this->apiResponse = [];
        }
    }

    //for normal api
    //not complete

    public function orderLists()
    {
        $requestData = $this->request->getQuery();
        $this->add_model(array('Api.AppUsers'));
        //$user = $this->AppUsers->find()->where(['api_token' => $this->jwtToken])->first();
        $userId = $this->jwtPayload->id;
        $orderType = \Api\Model\Service\Core::$orderType;
        $statusCore = \Api\Model\Service\Core::$status;
        $Transactions = TableRegistry::get('Api.Transactions');

        $orders = $Transactions->find('all')
            ->where(['Transactions.user_id' => $userId])
            ->where(['Transactions.status !=' => 0])
            ->where(['Transactions.status !=' => 4])
            ->contain('Companies')
            ->contain('BrokersList');

        $detail = $orders;
        $count = $orders->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;

        if (isset($requestData['search_value']) && !empty($requestData['search_value'])) {
            $search = $requestData['search_value'];

            $detail = $detail
                ->where([
                    'OR' => [
                        ['(Transactions.market) LIKE' => '%' . $search . '%'],
                        ['(Transactions.client_name) LIKE' => '%' . $search . '%'],
                        ['(Transactions.order_type) LIKE' => '%' . $search . '%'],
                        ['(Transactions.quantity_to_buy) LIKE' => '%' . $search . '%'],
                        ['(Transactions.status) LIKE' => '%' . $search . '%'],
                        ['(Companies.name) LIKE' => '%' . $search . '%'],
                        ['(Companies.symbol) LIKE' => '%' . $search . '%'],
                        ['(BrokersList.first_name) LIKE' => '%' . $search . '%'],
                        ['(BrokersList.last_name) LIKE' => '%' . $search . '%'],
                    ]]);

            $company_count = $detail;
            $totalFiltered = $company_count->count();
        }
        $columns = array(
            0 => 'Companies.name',
            1 => 'Companies.symbol',
            2 => 'Transactions.price',
            3 => 'Transactions.client_name',
            4 => 'BrokersList.first_name',
            5 => 'Transactions.order_type',
            6 => 'Transactions.fees',
            7 => 'Transactions.quantity_to_buy',
            8 => 'Transactions.market',
            9 => 'Transactions.action',
            10 => 'Transactions.total',
            11 => 'Transactions.created_at'
        );
        $order_col = isset($requestData['order_col']) ? $requestData['order_col'] : 0;
        $sidx = isset($columns[$order_col]) ? $columns[$order_col] : $columns[0];

        $sord = !isset($requestData['order_dir']) ? 'asc' : $requestData['order_dir'];
        $start = 0;
        $length = !isset($requestData['length']) ? 5 : $requestData['length'];
        $page = isset($requestData['page']) ? $requestData['page'] : 1;

        $results = $this->paginate($detail
            ->order($sidx . ' ' . $sord)
            ->limit((int)$length)
        );
        $paginate = $this->Paginator->configShallow(['data' => $results])->request->getParam('paging')['Transactions'];
        $data = $this->getResolvedData($results);
        $json_data = array(
            "data" => $data,
            "paginate" => $paginate
        );

        $this->apiResponse = $json_data;
    }

    private function getResolvedData($results)
    {
        $orderType = \Api\Model\Service\Core::$orderType;
        $statusCore = \Api\Model\Service\Core::$status;
        $Transactions = TableRegistry::get('Api.Transactions');
        $i = 0;
        $data = array();
        // $Stocks = TableRegistry::get('Api.Stocks');
        // $language = $this->currentLanguage;

        foreach ($results as $row) {
            foreach ($orderType as $key => $type) {
                if ($row["order_type"] === $key) {
                    $order = $type;
                }
            }
            foreach ($statusCore as $key => $status) {
                if ($row["status"] === $key) {
                    $statusTable = $status;
                }
            }
            if ($row["status"] === 1) {
                $class = 'badge-info';
            } elseif ($row["status"] === 2) {
                $class = 'badge-success';
            } elseif ($row["status"] === 3) {
                $class = 'badge-danger';
            }
            $nestedData = [];
            $nestedData['company_name'] = $row["company"]["name"];
            $nestedData['company_symbol'] = $row["company"]["symbol"];
            $nestedData['price'] = (string)$row["price"];
            $nestedData['investment_amount'] = (string)$row["investment_amount"];
            $nestedData['order_type'] = (string)$row["order_type"];
            $nestedData['investment_preferences'] = (string)$row["investment_preferences"];
            $nestedData['client_name'] = $row["client_name"];
            $nestedData['broker_name'] = $row["brokers_list"]["first_name"];
            //$nestedData['order'] = $order;
            $nestedData['fees'] = (string)$row["fees"];
            $nestedData['quantity_to_buy'] = (string)$row["quantity_to_buy"];
            $nestedData['market'] = (string)$row["market"];
            $nestedData['action'] = (string)$row["action"];
            $nestedData['total'] = (string)$row["total"];
            $nestedData['created_at'] = $row["created_at"]->nice();
            $nestedData['status'] = $statusTable;
            $data[] = $nestedData;
            $i++;

        }
        return $data;
    }

    //show all                          tions
    public function simulations()
    {
        $this->request->allowMethod('get');
        $this->paginate = [
            'limit' => 10,
        ];
        $userId = $this->jwtPayload->id;
        if ($userId) {
            $this->loadModel('Api.Watchlist');
            $this->loadModel('Api.SimulationSetting');
            $this->loadModel('Api.Stocks');
            $this->loadModel('Api.Brokers');
            $this->loadModel('Api.Simulations');

            $all_simulation = $this->paginate($this->Simulations->getSimulation($userId, $this->currentLanguage, $this->_getCurrentSubDomain()));
            $simulation_setting = $this->SimulationSetting->find('all')
                ->where(['SimulationSetting.user_id' => $userId])
                ->contain(['Brokers' => function ($q) {
                    return $q->autoFields(false);
                }])
                ->first();
            if (is_null($simulation_setting)) {
                if ($this->currentLanguage == self::JMD) {
                    $broker = $this->Brokers->find()
                        ->where(['Brokers.market' => self::JMD])
                        ->first();
                } else {
                    $broker = $this->Brokers->find()
                        ->where(['Brokers.market' => self::USD])
                        ->first();
                }
                $simulation_setting = (object)['investment_amount' => 10000,'quantity' => 100, 'broker' => $broker];
            };
            
            $getSimulations = $this->Simulations->getSimulation($userId, $this->currentLanguage, $this->_getCurrentSubDomain())->all();
            $totalPrice = 0;
            $totalInvAmount = 0;
            foreach ($getSimulations as $val) {
                $totalPrice += self::setSimulations($val->company->symbol, $val->company->id, $this->currentLanguage, $val->price, $simulation_setting->quantity);
                $totalInvAmount += $simulation_setting->quantity * $val->price;
            }

            if(!empty($simulation_setting)){
                $all_simul['totalInvAmount'] = $totalInvAmount;
                $all_simul['gainLoss'] = $totalPrice;
                $all_simul['account_value'] = (isset($simulation_setting->investment_amount) ? ($totalInvAmount + $totalPrice + $simulation_setting->investment_amount - $totalInvAmount) : 0);
                $all_simul['available_for_investing'] = $simulation_setting->investment_amount - $totalInvAmount;
            } else {
                $all_simul['totalInvAmount'] = null;
                $all_simul['gainLoss'] = null;
                $all_simul['account_value'] = null;
                $all_simul['available_for_investing'] = null;
            }
            $all_simuls = [];
            foreach ($all_simulation as $all_sim){
                $company=TableRegistry::get('Api.Companies');
                $query = $company->find()
                    ->where(['id' => $all_sim->company->id])
                    ->order(['id DESC'])
                    ->first();
                if ($this->currentLanguage == self::JMD) {
                    $stockInfo = $this->Stocks->getStockInformationLocal($query->symbol, $all_sim->company->id);
                } else {
                    $stockInfo = $this->Stocks->getStockInformation($query->symbol);
                }
                $gainLoss = $this->setSimulations($val->company->symbol, $val->company->id, $this->currentLanguage, Hash::get($stockInfo, 'info.open') + Hash::get($stockInfo, 'info.price_change'), $simulation_setting->quantity);
                $inv_amount = $simulation_setting->quantity * $val->price;
                $companyPrice = $this->setCompanyPrice($all_sim->price, $simulation_setting, $gainLoss, $inv_amount);
                $total = $companyPrice['total'];
                $all_simuls[] = [
                    'id'=>$all_sim->id,
                    'company_id'=>$all_sim->company_id,
                    'symbol' => $all_sim->company->symbol,
                    'quantity' => $simulation_setting->quantity,
                    'broker' => $simulation_setting->broker,
                    'price' => Hash::get($stockInfo, 'info.open') + Hash::get($stockInfo, 'info.price_change'),
                    'inv_amount' => $inv_amount,
                    'gain_loss' => $gainLoss,
                    'date_invested' =>  $all_sim->created_at->nice(),
                    'total' => $total
                ];
            }
            if($all_simulation){
                $all_simul['all_simulations'] = $all_simuls;
            } else {
                $all_simul['all_simulation'] = [];
            }
            $this->apiResponse['data'] = $all_simul;
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Simulations'];
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'Please login';
        }
    }

    //add simulation settings
    public function addSimulation(){
        $this->request->allowMethod('post');
        $this->add_model(array('Api.SimulationSetting'));
        $data = $this->request->getData();
        $user_id=$this->jwtPayload->id;
        try{
            if (isset($data)&&$user_id) {
                $addSimulation = $this->SimulationSetting->setSimulation($data,$user_id);
                if ($addSimulation) {
                    $this->apiResponse['message'] = 'Simulation added successfully.';
                }
                else{
                    $this->httpStatusCode = 404;
                    $this->apiResponse['error'] = 'Simulation cannot be added.';
                }     
            }
            else{
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'please insert data';
            }
        }catch (\Exception $e){
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = $e->getMessage();
        }
    }

    public static function setSimulations($symbol, $company_id, $current_language, $price, $count)
    {

        $stockInfo = TableRegistry::get('Api.Stocks');

        if ($current_language == self::JMD) {
            $stockInfo = $stockInfo->getStockInformationLocal($symbol, $company_id);
            $stock = $stockInfo['info']['open'] + $stockInfo['info']['price_change'];
        } else {
            $stockInfo = $stockInfo->getStockInformation($symbol);
            $stock = $stockInfo['info']['open'];
        }
        $price = $stock - $price;
        return $price * $count;
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

    /*delete users multiple simulation
     *@param simulation ids[]
     * */
    public function simulationDelete()
    {
        $this->loadModel('Api.Simulations');
        $data = $this->request->getData();
        $bool = false;
        if (!empty($data)) {
            foreach ($data['ids'] as $d){
                $simulation = $this->Simulations->find()
                    ->where(['user_id' => $this->jwtPayload->id, 'id' => $d])->first();
                if($simulation){
                    $this->Simulations->delete($simulation);
                    $bool = true;
                }
            }
            if($bool == false){
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = 'Simulation could not be deleted.';
            } else {
                $this->apiResponse['message'] = 'Simulation deleted successfully.';
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please enter simulation data';
        }
    }

    public function simulationsChart()
    {

        $id = $this->request->getQuery('id');
        $symbol = $this->request->getQuery('symbol');
        $companyId = $this->request->getQuery('company_id');
        $quantity = $this->request->getQuery('quantity');
        $date = $this->request->getQuery('date');
        $price = $this->request->getQuery('price');
        $gainLoss = $this->request->getQuery('gainLoss');
        $total = $this->request->getQuery('total');
        $fees = $this->request->getQuery('fees');
        $broker = $this->request->getQuery('broker');
        $inv_amount = $quantity * $price;
        
        $this->loadModel('Api.Stocks');
        if ($this->currentLanguage == self::JMD) { 
            $paramsChart = $this->Stocks->getStockSimulationChart($symbol, $companyId, $date, $quantity, $price);
            $stockInfo = $this->Stocks->getStockInformationLocal($symbol, $companyId);
            //dd($stockInfo);
        } else {
            $paramsChart = $this->Stocks->getStockSimulationUSDChart($symbol, $date, $price, $quantity);
            $stockInfo = $this->Stocks->getStockInformation($symbol);
        }
        
        if ($paramsChart) {
            $paramsChart = json_encode($paramsChart, true);
            
            $data['id'] = $id;
            $data['paramsChart'] = json_decode($paramsChart);
            $data['stockInfo'] = $stockInfo;
            $data['current_language'] = $this->currentLanguage;
            $data['quantity'] = $quantity;
            $data['gainLoss'] = $gainLoss;
            $data['total'] = $total;
            $data['inv_amount'] = $inv_amount;
            $data['fees'] = $fees;
            $data['broker'] = $broker;
            $this->apiResponse['data'] = $data;
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
        } else {
            $this->apiResponse['data'] = [];
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
        }
    }

    //not complete
    public function getSimulationsStockCurrentPrice()
    {
        $symbol = $this->request->getQuery('symbol');
        $company_id = $this->request->getQuery('company_id');

        $stockInfo = TableRegistry::get('Api.Stocks');

        $current_language = $this->currentLanguage;

        if ($current_language == self::JMD) {
            $stockInfo = $stockInfo->getStockInformationLocal($symbol, $company_id);
            $stock = $stockInfo['info']['open'] + $stockInfo['info']['price_change'];
        } else {
            $stockInfo = $stockInfo->getStockInformation($symbol);
            $stock = $stockInfo['info']['open'];
        }
        if ($stock) {
            $this->apiResponse['data'] = $stock;
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
        } else {
            $this->apiResponse['data'] = [];
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
        }
    }

    //get all currency
    public function getAllCurrency(){
        $currency = $this->currentLanguage;
        $list = array_flip($this->_currency::$currency);
        $array = [];
        foreach ($list as $val) {
            if ($val != $currency) {
                $array[] = $val ;
            }
        }
        $this->apiResponse['data'] = $array;
    }

    //get all pair for a currency
    public function getAllPair(){
        $requestData = $this->request->getQueryParams();
        $currency = $requestData['currency'];
        $list = array_flip($this->_currency::$currency);
        $array = [];
        foreach ($list as $key => $val) {

            if ($val != $currency) {
                $array[] = $currency . '-' . $val;
                $array[] = $val . '-' . $currency;
            }
        }
        $this->apiResponse['data'] = $array;
    }

    //get all price for a pair
    public function getAllPrice(){
        $requestData = $this->request->getQueryParams();
        $currency = $requestData['pair'];
        $this->loadModel('Trader');

        list($current_price, $trade_price) = $this->Trader->__getTraderInfoFromCurrencyPortfolio($currency);
        $response = [
            'current_price' => $current_price,
            'trade_price' => $trade_price,
        ];
        $this->apiResponse['data'] = $response;
    }
}
