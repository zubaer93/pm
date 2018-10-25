<?php

namespace Api\Controller;

use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class StocksController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');

    }

    /**
     * get all stocks
     *
     */
    public function stockList()
    {
        $requestData = $this->request->getQuery();
        if (isset($requestData['search_value']) && !empty($requestData['search_value'])) {
            $search_input_data = $requestData['search_value'];
        } else {
            $search_input_data = null;
        }
        try {
            $search_input_data = json_decode($search_input_data);
        } catch (\Exception $e) {

        }
        $search_input_data = $search_input_data ? $search_input_data : (!empty($requestData['search_value']) ? $requestData['search_value'] : '');
        $language = $this->currentLanguage;
        $Companies = TableRegistry::get('Api.Companies');
        $companies = $Companies->find('all')
            ->contain(['Exchanges' => function ($q) use ($language, $search_input_data) {
                $q = $q->autoFields(false);
                $q = $q->autoFields(false)
                    ->contain(['Countries' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => $language]);
                    }]);
                return $q;
            }])
            ->join(['sd_max' => [
                'table' => '(SELECT MAX(id) max_id, company_id FROM stocks_details GROUP BY company_id)',
                'type' => 'LEFT',
                'conditions' => 'Companies.id = sd_max.company_id',
            ]])
            ->join(['sd' => [
                'table' => 'stocks_details',
                'type' => 'LEFT',
                'conditions' => 'sd_max.max_id = sd.id',
            ]])
            ->join(['st_max' => [
                'table' => '(SELECT MAX(id) max_st_id, company_id FROM stocks GROUP BY company_id)',
                'type' => 'LEFT',
                'conditions' => 'Companies.id = st_max.company_id',
            ]])
            ->join(['st' => [
                'table' => 'stocks',
                'type' => 'LEFT',
                'conditions' => 'st_max.max_st_id = st.id',
            ]]);


        $companies->typeMap()
            ->addDefaults([
                'sd.market_cap' => 'integer',
                'sd.dividend_amount' => 'integer',
                'st.open' => 'integer',
                'st.volume' => 'integer',
            ]);

        if (isset($search_input_data->market_cap) && $search_input_data->market_cap) {
            $market_cap_value_exploded = explode('_', $search_input_data->market_cap);
            $from_val = $market_cap_value_exploded[0];
            $to_val = $market_cap_value_exploded[1];
            if ($from_val) {
                $companies->where(['sd.market_cap >= ' => $from_val]);
            }

            if ($to_val) {
                $companies->where(['sd.market_cap <= ' => $to_val]);
            }
        }

        if (isset($search_input_data->dividend_yield) && $search_input_data->dividend_yield) {
            $dividend_yield_value_exploded = explode('_', $search_input_data->dividend_yield);
            $from_val = $dividend_yield_value_exploded[0];
            $to_val = $dividend_yield_value_exploded[1];
            if ($from_val) {
                $companies->where(['sd.dividend_amount >= ' => $from_val]);
            }

            if ($to_val) {
                $companies->where(['sd.dividend_amount <= ' => $to_val]);
            }
        }

        if (isset($search_input_data->price) && $search_input_data->price) {
            $price_value_exploded = explode('_', $search_input_data->price);
            $from_val = $price_value_exploded[0];
            $to_val = $price_value_exploded[1];
            if ($from_val) {
                $companies->where(['st.open >= ' => $from_val]);
            }

            if ($to_val) {
                $companies->where(['st.open <= ' => $to_val]);
            }
        }

        if (isset($search_input_data->current_volume) && $search_input_data->current_volume) {
            $current_volume_value_exploded = explode('_', $search_input_data->current_volume);
            $from_val = $current_volume_value_exploded[0];
            $to_val = $current_volume_value_exploded[1];
            if ($from_val) {
                $companies->where(['st.volume >= ' => $from_val]);
            }

            if ($to_val) {
                $companies->where(['st.volume <= ' => $to_val]);
            }
        }

        if (isset($search_input_data->change) && $search_input_data->change) {
            $change_value_exploded = explode('_', $search_input_data->change);
            $from_val = $change_value_exploded[0];
            $to_val = $change_value_exploded[1];
            if ($from_val) {
                $companies->where(['(st.price_change *100)/st.open >= ' => $from_val]);
            }

            if ($to_val) {
                $companies->where(['(st.price_change *100)/st.open <= ' => $to_val]);
            }
        }

        if (isset($search_input_data->week_52_high) && $search_input_data->week_52_high) {
            $week_52_high_volume_value_exploded = explode('_', $search_input_data->week_52_high);
            $from_val = $week_52_high_volume_value_exploded[0];
            $to_val = $week_52_high_volume_value_exploded[1];
            if ($from_val) {
                $companies->where(['sd.high_price_52_week >= ' => $from_val]);
            }

            if ($to_val) {
                $companies->where(['sd.high_price_52_week <= ' => $to_val]);
            }
        }

        if (isset($search_input_data->week_52_low) && $search_input_data->week_52_low) {
            $week_52_low_volume_value_exploded = explode('_', $search_input_data->week_52_low);
            $from_val = $week_52_low_volume_value_exploded[0];
            $to_val = $week_52_low_volume_value_exploded[1];
            if ($from_val) {
                $companies->where(['sd.low_price_52_week >= ' => $from_val]);
            }

            if ($to_val) {
                $companies->where(['sd.low_price_52_week <= ' => $to_val]);
            }
        }

        if (isset($search_input_data->index) && $search_input_data->index) {
            $companies->where(['Companies.exchange_id' => $search_input_data->index]);
        }

        if (isset($search_input_data->sector) && $search_input_data->sector) {
            $companies->where(['Companies.sector' => $search_input_data->sector]);
        }

        if (isset($search_input_data->industry) && $search_input_data->industry) {
            $companies->where(['Companies.industry' => $search_input_data->industry]);
        }

        $detail = $companies;
        $count = $companies->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;

        if (is_string($search_input_data)) {
            $detail = $detail
                ->where([
                    'OR' => [
                        ['(Companies.name) LIKE' => '%' . $search_input_data . '%'],
                        ['(Companies.symbol) LIKE' => '%' . $search_input_data . '%'],
                        ['(Companies.sector) LIKE' => '%' . $search_input_data . '%'],
                        ['(Exchanges.name) LIKE' => '%' . $search_input_data . '%'],
                        ['(Countries.market) LIKE' => '%' . $search_input_data . '%']
                    ]]);
        } else {
            if (isset($search_input_data->query) && $search_input_data->query) {
                $search = $search_input_data->query;
                $detail = $detail
                    ->where([
                        'OR' => [
                            ['(Companies.name) LIKE' => '%' . $search . '%'],
                            ['(Companies.symbol) LIKE' => '%' . $search . '%'],
                            ['(Companies.sector) LIKE' => '%' . $search . '%'],
                            ['(Exchanges.name) LIKE' => '%' . $search . '%'],
                            ['(Countries.market) LIKE' => '%' . $search . '%']
                        ]]);
                $company_count = $detail;
                $totalFiltered = $company_count->count();
            }
        }


        $columns = array(
            0 => 'Companies.symbol',
            1 => 'Companies.name',
            6 => 'Exchanges.name',
            7 => 'Companies.sector',
        );
        $order_col = isset($requestData['order_col']) ? $requestData['order_col'] : 0;
        $sidx = isset($columns[$order_col]) ? $columns[$order_col] : $columns[0];

        $sord = !isset($requestData['order_dir']) ? 'asc' : $requestData['order_dir'];

        $results = $this->paginate($detail
            ->order($sidx . ' ' . $sord)
        );
        $paginate = $this->Paginator->configShallow(['data' => $results])->request->getParam('paging')['Companies'];
        $data = $this->getResolvedData($results);
        $json_data = array(
            "data" => $data,
            "draw" => intval(!empty($requestData['draw']) ? $requestData['draw'] : 1),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "paginate" => $paginate
        );
        $this->apiResponse = $json_data;
    }

    //dataTable  stocks
    public function ajaxManageCompanySearch()
    {
        //$this->request->allowMethod('post');
        $requestData = $this->request->getQuery();
        $obj = new \Api\Model\DataTable\CompanyFrontDataTable();
        try {
            $result = $obj->ajaxManageCompanySearch($requestData, $this->currentLanguage);
        } catch (\Exception $e) {
            $this->apiResponse['error'] = $e->getMessage();
            $this->httpStatusCode = 503;
        }
        if (!empty($result)) {
            $this->apiResponse = $result;
        } else {
            $this->apiResponse = [];
        }
    }

    /**
     * add stocks
     *
     */
    public function add()
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.Stocks'));
        $data = $this->request->getData();
        //dd($data);
        if (!empty($data)) {
            $stock = $this->Stocks->newEntity();
            $stock = $this->Stocks->patchEntity($stock, $data);
            if ($this->Stocks->save($stock)) {
                $this->apiResponse['message'] = 'Stock has been saved successfully.';
            } else {
                $this->apiResponse['error'] = 'Stock could not be saved. Please try again.';
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please enter stock info.';
        }
    }

    /**
     * edit stocks
     * @param stock_id
     *
     */
    public function edit($stock_id = null)
    {
        $this->request->allowMethod('post');
        $this->add_model(array('Api.Stocks'));
        if (!empty($stock_id)) {
            $stock = $this->Stocks->get($stock_id);
            if (!empty($data = $this->request->getData())) {
                $stock = $this->Stocks->patchEntity($stock, $data);
                if ($this->Stocks->save($stock)) {
                    $this->apiResponse['message'] = 'Stock has been updated successfully.';
                } else {
                    $this->httpStatusCode = 404;
                    $this->apiResponse['error'] = 'Stock could not be updated. Please try again.';
                }
            } else {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'No data has found. Please enter a stock information.';
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'No data has found. Please enter stock id';
        }
    }

    public function top()
    {
        $this->loadModel('Api.Stocks');
        $topStocks = $this->Stocks->getStockTopPerformInformation($this->currentLanguage);
        if (!empty($topStocks)) {
            $this->apiResponse['data'] = $topStocks;
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
        } else {
            $this->apiResponse['data'] = [];
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
        }
    }

    public function worst()
    {
        $this->loadModel('Api.Stocks');
        $worstStocks = $this->Stocks->getStockWorstPerformInformation($this->currentLanguage);
        if (!empty($worstStocks)) {
            $this->apiResponse['data'] = $worstStocks;
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
        } else {
            $this->apiResponse['data'] = [];
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
        }
    }

    public function getStockBySymbol($symbol)
    {
        $this->request->allowMethod('get');
        $this->loadModel('Api.Stocks');
        $this->loadModel('Api.Companies');
        $language = $this->currentLanguage;
        try {
            if($language == "JMD"){
                $companyInfo = $this->Companies->getCompanyInfo($symbol, $language);
                $stocks = $this->Stocks->getStockInformationLocal($symbol, $companyInfo['id']);
            }
            elseif($language == "USD" ){
                $stocks = $this->Stocks->getStockInformation($symbol);
            }
            if (!empty($stocks)) {
                $this->apiResponse['data'] = $stocks;
            } else {
                $this->apiResponse['data'] = null;
            }
        } catch (\Exception $e) {
            $this->apiResponse['error'] = $e->getMessage();
            $this->httpStatusCode = 404;
        }
        return;
    }
    
    public function info()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.Stocks'));
        $data = $this->request->getQueryParams();
        if (!empty($data['company_id']) && !empty($data['type']) && !empty($data['symbol'])) {
            if ($data['type'] == 'local') {
                $stockInfo = $this->Stocks->getStockInformationLocal($data['symbol'], $data['company_id']);
            } elseif ($data['type'] == 'chart') {
                $stockInfo = $this->Stocks->getStockInformation($data['symbol']);
            }
            if (!empty($stockInfo)) {
                $this->apiResponse['data'] = $stockInfo;
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
            } else {
                $this->apiResponse['data'] = [];
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No data has found. Please enter a company ID, type and symbol';
        }
    }

    public function delete($stock_id = null)
    {
        $this->request->allowMethod('delete');
        $this->add_model(array('Api.Stocks'));
        if (!empty($stock_id)) {
            try {
                $stock = $this->Stocks->get($stock_id);
                if ($this->Stocks->delete($stock)) {
                    $this->apiResponse['message'] = 'Stock has been deleted successfully.';
                } else {
                    $this->httpStatusCode = 404;
                    $this->apiResponse['error'] = 'Stock could not be deleted. Please try again.';
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = $e->getMessage();;
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'No data has found. Please enter stock id';
        }
    }

    public function simulationChart()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.Stocks'));
        $data = $this->request->getQueryParams();

        if (!empty($data['date']) && !empty($data['price']) && !empty($data['quantity']) && !empty($data['symbol'])) {
            if ($this->currentLanguage == 'USD') {
                $simulation_chart = $this->Stocks->getStockSimulationUSDChart($data['symbol'], $data['price'], $data['date'], $data['quantity']);
            } else {
                $simulation_chart = $this->Stocks->getStockSimulationChart($data['symbol'], $data['company_id'], $data['date'], $data['quantity'], $data['price']);
            }
            if (!empty($simulation_chart)) {
                $this->apiResponse['data'] = $simulation_chart;
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
            } else {
                $this->apiResponse['data'] = [];
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Stocks'];
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No data has found. Please enter necessary query params';
        }
    }

    private function getResolvedData($results)
    {
        $i = 0;
        $data = array();
        $Stocks = TableRegistry::get('Api.Stocks');
        $language = $this->currentLanguage;
        foreach ($results as $row) {
            if ($language == 'JMD') {
                $stockInfo = $Stocks->getStockInformationLocal($row["symbol"], $row['id']);
            } else {
                $stockInfo = $Stocks->getStockInformation($row["symbol"]);
            }

            $price = $stockInfo['info']['open'] - $stockInfo['info']['close'];

            $change = 0;
            if ($stockInfo['info']['open'] > 0) {
                $change = (($price) / $stockInfo['info']['open']);
            }


            $nestedData = [];
            $nestedData['company_id'] = $row["id"];
            $nestedData['symbol'] = $row["symbol"];
            $nestedData['company_name'] = $row["name"];
            $nestedData['price'] = $stockInfo['info']['open'];
            $nestedData['changePercentage'] = $change;
            $nestedData['changePrice'] = $price;
            $nestedData['vol'] = $stockInfo['info']['volume'];
            $nestedData['index'] = $row["exchange"]["name"];
            $nestedData['sector'] = $row["sector"];
            $data[] = $nestedData;
            $i++;

        }
        return $data;
    }

    ///send dropdown lists of stock filters
    public function stocksFilter()
    {
        Configure::load('Api.filters', 'default');

        $this->add_model(array('Api.Exchanges', 'Api.Countries'));
        $countryID = $this->Countries->find()->where(['market' => $this->currentLanguage])->first();
        $index = $this->Exchanges->find()
            ->where(['country_id' => $countryID['id']])
            ->select(['value' => 'id', 'text' => 'name'])
            ->toArray();

        $this->add_model(array('Api.Companies'));
        $sector = $this->Companies->find();
        $sector->select(['value' => 'sector', 'text' => 'sector'])
            ->distinct(['sector']);

        $industry = $this->Companies->find()->select(['value' => 'industry', 'text' => 'industry'])
            ->distinct(['industry']);

        $country = $this->Countries->find()
            ->select(['value' => 'id', 'text' => 'name'])
            ->toArray();

        $market_cap = Configure::read('market_cap');

        $dividend_yield = Configure::read('dividend_yield');

        $current_volume = Configure::read('dividend_yield');

        $price = Configure::read('price');

        $ipo_date = Configure::read('ipo_date');

        $change = Configure::read('change');

        $week_high = Configure::read('52_week_high');

        $week_low = Configure::read('52_week_high');

        $filter_data = array(
            ['text' => 'Index', 'value' => 'index', 'data' => $index],
            ['text' => 'Sector', 'value' => 'sector', 'data' => $sector],
            ['text' => 'Country', 'value' => 'country', 'data' => $country],
            ['text' => 'Industry', 'value' => 'industry', 'data' => $industry],
            ['text' => 'Current Volume', 'value' => 'current_volume', 'data' => $current_volume],
            ['text' => 'Dividend Yield', 'value' => 'dividend_yield', 'data' => $dividend_yield],
            ['text' => 'Market Capital', 'value' => 'market_cap', 'data' => $market_cap],
            ['text' => 'Price', 'value' => 'price', 'data' => $price],
            ['text' => 'Ipo Date', 'value' => 'ipo_date', 'data' => $ipo_date],
            ['text' => 'Change', 'value' => 'change', 'data' => $change],
            ['text' => '52 Week High', 'value' => '52_week_high', 'data' => $week_high],
            ['text' => '52 Week Low', 'value' => '52_week_low', 'data' => $week_low]
        );

        if (!empty($filter_data)) {
            $this->apiResponse['data'] = $filter_data;
        } else {
            $this->apiResponse['data'] = [];
        }
    }


}
