<?php

namespace Api\Controller;

use Api\Model\Table\PrivateTable;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Analysis Controller
 *
 * @property \App\Model\Table\AnalysisTable $Analysis
 *
 * @method \App\Model\Entity\Analysi[] paginate($object = null, array $settings = [])
 */
class AnalysisController extends AppController
{

    const JMD = 'JMD';
    const USD = 'USD';

    public $paginate = [
        'limit' => 10
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    public function companyFinancial($symbol)
    {

        $this->loadModel('Api.Companies');
        $language = $this->currentLanguage;

        $this->loadModel('Api.FinancialStatement');
        $statement = $this->FinancialStatement->find('all')->contain([
            'Companies' => function ($q) use ($language, $symbol) {
                return $q->autoFields(false)->where(['Companies.symbol' => $symbol])
                    ->contain(['Exchanges' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->contain(['Countries' => function ($q) use ($language) {
                                return $q->autoFields(false)
                                    ->where(['Countries.market' => $language]);
                            }]);
                    }]);
            }])
            ->orderDesc('FinancialStatement.id');
        $this->apiResponse = !empty($statement) ? ['data' => $statement] : ['data' => []];
    }

    /**
     *
     * @param type $symbol
     */
    public function sector($symbol)
    {
        $this->loadModel('Api.Companies');
        $this->loadModel('Api.SectorPerformances');
        
        $companyInfo = $this->Companies->getCompanyInfo($symbol, $this->currentLanguage);
        $sector = $this->Companies->getCompanisBySector($companyInfo['sector'], $this->currentLanguage, 20);
        $stocks = $this->Companies->getStocksInfo($sector, $this->currentLanguage);
        
        $sectorPerformance = $this->SectorPerformances->getSectorPerformances2($this->currentLanguage, $companyInfo['sector']);
        $array = array_keys($sectorPerformance);
        $array2 = array_values($sectorPerformance);
        $sectorPerformance = [];
        for ($i = 0; $i < count($array); $i++) {
            $a = substr($array2[$i], 0, -1);
            $sectorPerformance[] = array('name' => $array[$i], 'percent' => $a);
        }
        $sectorName = $companyInfo['sector'];
        $percent = null;
        foreach($sectorPerformance as $sp){
            if($sp['name'] == $companyInfo['sector']){
                $sectorName = $sp['name'];
                $percent = $sp['percent'];
            }
        }
       
        $this->apiResponse['sectorName'] = $sectorName ;
        $this->apiResponse['percent'] = $percent ;
        $this->apiResponse['stocks'] = $stocks ;
    }
    /**
     *
     * @param type $symbol
     */
    public function industry($symbol)
    {
        $this->loadModel('Api.Companies');
        $companyInfo = $this->Companies->getCompanyInfo($symbol, $this->currentLanguage);
        $industry = $this->Companies->getCompaniesByIndustry($companyInfo['industry'], $this->currentLanguage);
        $stocks = $this->Companies->getStocksInfo($industry, $this->currentLanguage);

        $this->apiResponse = !empty($stocks) ? ['data' => $stocks] : ['data' => []];
    }
    /**
     *
     * @param type $symbol
     */
    public function getChartData($symbol = null)
    {
        $this->loadModel('Api.Stocks');
        $this->loadModel('Api.Companies');
        try{
            $companyInfo = $this->Companies->getCompanyInfo($symbol, $this->currentLanguage);
            $stockInfo = $this->Stocks->getStockInformationChart($companyInfo['symbol'], $companyInfo['id']);
        } catch (\Exception $e) {
            $this->apiResponse['error'] = $e->getMessage();
            $this->httpStatusCode = 503;
        }
        $this->apiResponse = !empty($stockInfo) ? ['data' => $stockInfo] : ['data' => []];
    }
 /**
     * 
     * @param type $symbol
     */
    public function sectorModal($symbol)
    {
        $this->loadModel('Api.Stocks');
        $this->loadModel('Api.Companies');
        $companyInfo = $this->Companies->getCompanyInfo($symbol, $this->currentLanguage);
        $sector = $this->Companies->getCompanisBySector($companyInfo['sector'], $this->currentLanguage, 5);
    
        $language = $this->currentLanguage;
        $options = [];
        foreach ($sector as $val) {
            $companyInfo = $this->Companies->getCompanyInfo($val['symbol'], $language);
            $stockInfo = $this->Stocks->getStockInformationLocal($val['symbol'], $companyInfo['id']);
            $options[$companyInfo['id']]['companyInfo'] = $companyInfo;
            $options[$companyInfo['id']]['stockDetail'] = $this->Stocks->getStockMarketDepth2($symbol, $language, $companyInfo['id'], $stockInfo['info']['open'], (isset($stockInfo['info']['eps']) ? $stockInfo['info']['eps'] : ''));
        }
        $data['options'] = $options;
        $data['symbol'] = $symbol;

        $this->apiResponse = !empty($data) ? ['data' => $data] : ['data' => []];
    }
     /**
     * 
     * @param type $stock
     * @return type
     */
    public function index($symbol)
    {
        
        $stockTopInfo = [];
        $stockWorstInfo = [];
        $option = [];
        if(isset($this->jwtPayload->id)){
            $userId = $this->jwtPayload->id;
        }
        else{
            $userId = null;
        }
      
        $language = $this->currentLanguage;

        $this->loadModel('Api.Stocks');
        $this->loadModel('Api.Events');
        $this->loadModel('Api.SectorPerformances');
        $this->loadModel('Api.FinancialStatement');
        $this->loadModel('Api.BuySellBroker');
        $this->loadModel('Api.Companies');
        
        $companyInfo = $this->Companies->getCompanyInfo($symbol, $language, $userId);

        $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);

        if ($language == self::JMD) {
            $stockInfo = $this->Stocks->getStockInformationLocal($symbol, $companyInfo['id']);
            $events = $this->Events->getEvent($companyInfo['id']);
            $option = $this->Stocks->getStockMarketDepth2($symbol, $language, $companyInfo['id'], $stockInfo['info']['open'], (isset($stockInfo['info']['eps']) ? $stockInfo['info']['eps'] : ''));

        } else {
            $stockInfo = $this->Stocks->getStockInformation($symbol);
            $option = $this->Stocks->getStockMarketDepth2($symbol, $language, $companyInfo['id'], $stockInfo['info']['open'], (isset($stockInfo['info']['eps']) ? $stockInfo['info']['eps'] : ''));
            $events = null;
        }

        $analysis = $this->Analysis->find()
            ->where([
                'user_id' => $userId,
                'company_id' => $companyInfo['id']
            ])
            ->contain(['AnalysisNews'])
            ->contain('AnalysisTags')
            ->contain('AnalysisSymbols')
            ->contain('AnalysisWatchList')
            ->contain('AnalysisType')
            ->first();

        $statement = $this->FinancialStatement->getFinancialStatements($symbol, $language);

        $brokers_details = $this->BuySellBroker->find()
            ->contain(['Brokers'])
            ->where(['BuySellBroker.company_id' => $companyInfo['id']])
            ->orderDesc('BuySellBroker.created_at')->toArray();

        $rating = array();
        $rating_data = array();
        foreach($brokers_details as $broker){
            $rating = [];
            $rating['broker_id'] = $broker['broker']['id'];
            $rating['broker_name'] = $broker['broker']['first_name'].' '.$broker['broker']['last_name'];
            $rating['status'] = $broker['status'];
            $rating['date'] = $broker['created_at'];
            $rating_data[] = $rating;
        }

        $this->loadModel('WatchlistGroup');
        $watchlistStock = $this->WatchlistGroup->getList($userId);

        $data['companyInfo'] = $companyInfo;
        $data['rating'] = $rating_data;
        $data['stock_summary'] = $option;
        $data['stockInfo'] = $stockInfo;
        $data['analysis'] = $analysis;
        $data['events'] = $events;
        $data['watchlist'] = $watchlistStock;
        $data['statement'] = $statement;
        

        $this->apiResponse = !empty($data) ? ['data' => $data] : ['data' => []];

    }
    public function timeAndSales($symbol)
    {
        $lng = $this->currentLanguage;
        $Companies = TableRegistry::get('Api.Companies');
        $company_id = $Companies->getCompanyId($symbol, $lng);

        $Stocks = TableRegistry::get('Api.Stocks');
        $stocks = $Stocks->getAllStocksFromCompanyId($company_id);
        $detail = $stocks;
        $count = $stocks->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(Stocks.volume) LIKE' => '%' . $search . '%'],
                    ['(Stocks.quantity) LIKE' => '%' . $search . '%'],
                    ['(Stocks.open) LIKE' => '%' . $search . '%'],
                    ['(Stocks.last_refreshed) LIKE' => '%' . $search . '%'],
            ]]);
            $stocks_count = $detail;
            $totalFiltered = $stocks_count->count();
        }

        $columns = array(
            0 => 'Stocks.open',
            2 => 'Stocks.quantity',
            3 => 'Stocks.volume',
            4 => 'Stocks.last_refreshed',
        );

        $sidx =  $columns[4];
        $sord =  'DESC';

        $results = $this->paginate($detail
        ->order($sidx . ' ' . $sord)
        );
        $i = 0;
        $paginate = $this->Paginator->configShallow(['data' => $results])->request->getParam('paging')['Stocks'];
        $data = array();
        foreach ($results as $row) {
            $close = $row['open'] + (-1 * $row['price_change']);

            if ($row['open'] - $close >= 0) {
                $class = "positive";
            } else {
                $class = "negative";
            }
            $nestedData = [];
            $nestedData['open'] = $row['open'];
            $nestedData['class'] =  $class;
            $nestedData['quantity'] = $row['quantity'];
            $nestedData['volume'] = $row['volume'];
            $nestedData['last_refreshed'] = date('Y-m-d H:i:s', strtotime($row["last_refreshed"]));

            $data[] = $nestedData;
            $i++;
        }
        $json_data = array(
            "data" => $data,
            "paginate" => $paginate
        );
        $this->apiResponse = $json_data;
    }
    
      /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function addAnalysis($symbol)
    {
        $this->request->allowMethod('post');
        $requsetData = $this->request->getData();
        return $this->addAnalysisFunction($requsetData, $symbol);
    }

    /**
     * 
     */
    public function addAnalysisModal()
    {
        $requsetData = $this->request->getData();
        return $this->addAnalysisFunction($requsetData, $requsetData['symbol']);
    }

    /**
     * 
     * @param type $requsetData
     * @param type $symbol
     * @return type
     */
    private function addAnalysisFunction($requsetData, $symbol)
    {
        $this->loadModel('Api.Analysis');
        $this->loadModel('Api.Companies');
        $language = $this->currentLanguage;
        $companyInfo = $this->Companies->getCompanyInfo($requsetData['symbol'], $language);
        //dd($companyInfo);
        $userId = $this->jwtPayload->id;
        $entity = $this->Analysis->find('all')
                ->where(['user_id' => $userId])
                ->where(['company_id' => $companyInfo['id']])
                ->first();
        if (!isset($requsetData['row_id'])) {
            $requsetData['company_id'] = $companyInfo['id'];
            $requsetData['user_id'] = $userId;
            $analysi = $this->Analysis->add($requsetData);

            if ($analysi) {
                $this->loadModel('Api.AnalysisNews');
                $this->AnalysisNews->add($requsetData['news'], $analysi['id']);

                $this->loadModel('Api.AnalysisSymbols');
                $this->AnalysisSymbols->add($requsetData['symbol'], $analysi['id']);

                $this->loadModel('Api.AnalysisTags');
                $this->AnalysisTags->add($requsetData['tags'], $analysi['id']);

                $this->loadModel('Api.AnalysisWatchList');
                $this->AnalysisWatchList->add($requsetData['watch_list'], $analysi['id']);

                $bool = true;
            } else {
                $bool = false;
            }
            if ($bool) {
                $this->apiResponse['message'] = 'Analysis is saved';
                $this->apiResponse['data'] = $analysi;
            } else {
                $this->httpStatusCode = 400;// bad request
                $this->apiResponse['error'] = 'The analysis could not be saved. Please, try again.';
            }
        } else {
            $analysi = $this->Analysis->get($requsetData['row_id'], [
                'contain' => []
            ]);
            $requsetData['company_id'] = $companyInfo['id'];
            $requsetData['user_id'] = $userId;

            $analysi = $this->Analysis->patchEntity($analysi, $requsetData);

            if ($this->Analysis->save($analysi)) {

                $this->loadModel('Api.AnalysisNews');
                $this->AnalysisNews->add($requsetData['news'], $analysi['id']);

                $this->loadModel('Api.AnalysisSymbols');
                $this->AnalysisSymbols->add($requsetData['symbol'], $analysi['id']);

                $this->loadModel('Api.AnalysisTags');
                $this->AnalysisTags->add($requsetData['tags'], $analysi['id']);

                $this->loadModel('Api.AnalysisWatchList');
                $this->AnalysisWatchList->add($requsetData['watch_list'], $analysi['id']);

                $bool = true;
            } else {
                $bool = false;
            }
            if ($bool) {
                $this->apiResponse['message'] = 'The analysis has been edited.';
                $this->apiResponse['data'] = $analysi;
            } else {
                $this->httpStatusCode = 400;// bad request
                $this->apiResponse['error'] = 'The analysis could not be edited. Please, try again.';
            }
        }
    }
    /**
     * Delete method
     *
     * @param string|null $id Analysis id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteAnalysis()
    {
        $requestData = $this->request->getData('data');
        $this->loadModel('Api.Analysis');
        $bool = null;
        $array = [];
        try {
            $userId = $this->jwtPayload->id;
            foreach ($requestData as $data) {
                $analysis = $this->Analysis->find()
                        ->where(['user_id' => $userId])
                        ->where(['id' => $data])
                        ->first();
                if($analysis){
                    if (!$this->Analysis->delete($analysis)) {
                        $bool = false;
                    } else {
                        $bool = true;
                        $array[] = $data;
                    }
                }
                else{
                    $this->httpStatusCode = 400;// bad request
                    $this->apiResponse['error'] = 'You are not authorized to delete this';
                    return;
                }
            }
            if ($bool) {
                $this->apiResponse['message'] = 'Analysis succesfully deleted';
                $this->apiResponse['data'] = $array;
            } else {
                $this->httpStatusCode = 400;// bad request
                $this->apiResponse['error'] = 'The analysis could not be deleted. Please, try again.';
            }
        } catch (\Exception $e) {
            $this->apiResponse['error'] = $e->getMessage();
            $this->httpStatusCode = 503;
        }
    }
     /**
     * All method
     *
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function all()
    {
        if (!$this->jwtPayload->id) {
            $this->httpStatusCode = 400;// bad request
            $this->apiResponse['error'] = 'Please login';
            return;
        }
        $userId = $this->jwtPayload->id;
        $language = $this->currentLanguage;
        $this->loadModel('Api.AnalysisNews');
        $allAnalysis =  $this->paginate($this->Analysis->find()
            ->where(['user_id' => $userId])
             ->contain([
                'AnalysisType',
                'AnalysisNews.News',
                'AnalysisTags',
                'AnalysisWatchList.WatchlistGroup',
                'Companies',
             ])
        );
        
        $paginate = $this->Paginator->configShallow(['data' => $allAnalysis])->request->getParam('paging')['Analysis'];
        $data = array();
        
        foreach ($allAnalysis as $all) {
            $nestedData = [];
            $nestedtags = [];
            $nestedWatchlist = [];
            $nestedNews = [];

            $nestedData['id'] = $all["id"];
            $nestedData['name'] = $all["name"];
            $nestedData['text'] = $all["text"];
            $nestedData['symbol'] = $all["company"]['symbol'];
            $nestedData['created'] = $all["created_at"];
            $nestedData['last_modified'] = $all["created_at"];
            $nestedData['approve'] = $all["approve"];
            $nestedData['type']['id'] = $all["analysis_type"]['id'];
            $nestedData['type']['name'] = $all["analysis_type"]['name'];

            foreach($all["analysis_watch_list"] as $watchlist){
                $nestedWatchlist['id'] = $watchlist['watch_list_group_id'];
                $nestedWatchlist['name'] = $watchlist['watchlist_group']['name'];

                $nestedData['watchlist'][] = $nestedWatchlist;
            }
            foreach($all["analysis_tags"] as $tags){
                $nestedtags['id'] = $tags['id'];
                $nestedtags['name'] = $tags['name'];

                $nestedData['tags'][] = $nestedtags;
            }
            foreach($all["analysis_news"] as $news){
                $nestedNews['id'] = $news['news_id'];
                $nestedNews['title'] = $news['news']['title'];

                $nestedData['news'][] = $nestedNews;
            }
            $data[] = $nestedData;
        }
        $this->apiResponse['data'] = $data;   
        $this->apiResponse['paginate'] = $paginate;   
         
    }

    public function analysisEditPartial()
    {

        $requestData = $this->request->getQuery('data');
        if (!$this->jwtPayload->id) {
            $this->httpStatusCode = 400;// bad request
            $this->apiResponse['error'] = 'Please login';
            return;
        } else {
            $userId = $this->jwtPayload->id;
            $analysis = $this->Analysis->find()
                    ->where(['Analysis.user_id' => $userId])
                    ->where(['Analysis.id' => (int) $requestData])
                    ->contain(['AnalysisNews' => function ($q)
                        {
                            return $q->autoFields(false)
                                    ->contain(['News' => function ($q)
                                        {
                                            return $q->autoFields(false);
                                        }]);
                        }])
                    ->contain('AnalysisTags')
                    ->contain('AnalysisSymbols')
                    ->contain(['AnalysisWatchList' => function ($q)
                        {
                            return $q->autoFields(false)
                                    ->contain(['WatchlistGroup' => function ($q)
                                        {
                                            return $q->autoFields(false);
                                        }]);
                        }])
                    ->contain('AnalysisType')
                    ->first();

            $companyInfo = $this->Analysis->Companies->get($analysis['company_id']);
            $companyMarket = $this->Analysis->Companies->getCompanyMarket($companyInfo['exchange_id']);
            $this->loadModel('Api.Stocks');
            if ($this->currentLanguage == self::JMD) {
                $stockInfo = $this->Stocks->getStockInformationLocal($companyInfo['symbol'], $companyInfo['id']);
            } else {
                $stockInfo = $this->Stocks->getStockInformation($companyInfo['symbol']);
            }
            $this->loadModel('Api.WatchlistGroup');
            $watchlistList = $this->WatchlistGroup->getAllWatchlists($userId);
            $watchlistList_array = [];
            
            foreach ($watchlistList as $val) {
                $watchlistList_array[$val['id']] = $val['name'];
            }

            $this->loadModel('Api.News');
            $news = $this->News->getCompanyNews($companyInfo['symbol'], $this->currentLanguage, $companyInfo['name']);
            $news_array = [];
            foreach ($news as $val) {
                $news_array[$val['id']] = $val['title'];
            }
            $this->apiResponse['analysis'] = $analysis;        
            $this->apiResponse['stockInfo'] = $stockInfo;        
            $this->apiResponse['news_array'] = $news_array;        
            $this->apiResponse['watchlistList_array'] = $watchlistList_array;        
            $this->apiResponse['companyInfo'] = $companyInfo;   
            $this->apiResponse['companyMarket'] = $companyMarket;   
            $this->apiResponse['requestData'] = $requestData;   
        }
    }

    public function analysisApprove()
    {
        $requestData = $this->request->getQuery('data');
        if($requestData){
            if (!$this->jwtPayload->id) {
                $this->httpStatusCode = 400;// bad request
                $this->apiResponse['error'] = 'Please login';
                return;
            } else {
                $userId = $this->jwtPayload->id;
                $analysis = $this->Analysis->find()
                        ->where(['Analysis.user_id' => $userId])
                        ->where(['Analysis.id' => (int) $requestData])
                        ->first();
                if (!is_null($analysis)) {
                    $array['id'] = $requestData;
                    if ($analysis['approve'] == 0) {
                        $array['approve'] = 1;
                    } else {
                        $array['approve'] = 0;
                    }

                    $result = $this->Analysis->updateAnalysis($array);

                    if ($result) {
                        if($array['approve'] == 1){
                        $this->apiResponse['message'] = 'Analysis approved';
                        }
                        else{
                            $this->apiResponse['message'] = 'Analysis not approved';
                        }
                    } else {
                        $this->httpStatusCode = 400;// bad request
                        $this->apiResponse['error'] = 'The analysis could not be approved. Please, try again.';
                    }
                }
                else{
                    $this->httpStatusCode = 400;// bad request
                    $this->apiResponse['error'] = 'You are not authorized'; 
                }
            }
        }else{
            $this->httpStatusCode = 400;// bad request
            $this->apiResponse['error'] = 'Insert query parameter data';
        }
    }

    public function makeCopy()
    {
        $id = $this->request->getData('data');
        if($id){
            $userId = $this->jwtPayload->id;
            $allNews = $this->Analysis->find()
                    ->where(['Analysis.user_id' => $userId])
                    ->where(['Analysis.id' => (int) $id])
                    ->contain('AnalysisNews')
                    ->contain('AnalysisTags')
                    ->contain('AnalysisSymbols')
                    ->contain(['AnalysisWatchList' => function ($q)
                        {
                            return $q->autoFields(false)
                                    ->contain(['WatchlistGroup' => function ($q)
                                        {
                                            return $q->autoFields(false);
                                        }]);
                        }])
                    ->contain('AnalysisType')
                    ->first();
            $companyInfo = $this->Analysis->Companies->get($allNews->company_id);
            
            $watch_list = [];
            foreach ($allNews['analysis_watch_list'] as $list) {
                $watch_list[] = $list->watch_list_group_id;
            }
            $news = [];
            foreach ($allNews['analysis_news'] as $val) {
                $news[] = $val->news_id;
            }
            $tags = [];
            foreach ($allNews['analysis_tags'] as $val) {
                $tags[] = $val->name;
            }
            $data = [
                'text' => $allNews->text,
                'files' => '',
                'name' => $allNews->name.' (copy)',
                'type' => $allNews->type,
                'watch_list' => $watch_list,
                'news' => $news,
                'tags' => $tags,
                'symbol' => $companyInfo->symbol
            ];
            return $this->addAnalysisFunction($data, $companyInfo->symbol);
        }else{
            $this->httpStatusCode = 400;// bad request
            $this->apiResponse['error'] = 'insert analysis id as data'; 
        }
    }


    /**
     * 
     */
    protected function dataPrint()
    {
        if (!$this->jwtPayload->id) {
            $this->httpStatusCode = 400;// bad request
            $this->apiResponse['error'] = 'Please login';
            return;
        } 
        $userId = $this->jwtPayload->id;
        $user = $this->jwtPayload->username;

        $this->loadModel('Api.Stocks');
        $id = $this->request->getQuery('data');
        $analysis = $this->Analysis->find('all')
                ->where(['Analysis.id' => $id])
                ->contain('Companies')
                ->first();

        $companyMarket = $this->Analysis->Companies->getCompanyMarket($analysis['company']['exchange_id']);

        if (self::JMD == $companyMarket) {
            $stockInfo = $this->Stocks->getStockInformationLocal($analysis['company']['symbol'], $analysis['company']['id']);
        } else {
            $stockInfo = $this->Stocks->getStockInformation($analysis['company']['symbol']);
        }

        $allNews = $this->Analysis->find()
                ->where(['Analysis.user_id' => $userId])
                ->where(['Analysis.id' => (int) $id])
                ->contain(['AnalysisNews' => function ($q)
                    {
                        return $q->autoFields(false)
                                ->contain(['News' => function ($q)
                                    {
                                        return $q->autoFields(false);
                                    }]);
                    }])
                ->contain('AnalysisTags')
                ->contain('AnalysisSymbols')
                ->contain(['AnalysisWatchList' => function ($q)
                    {
                        return $q->autoFields(false)
                                ->contain(['WatchlistGroup' => function ($q)
                                    {
                                        return $q->autoFields(false);
                                    }]);
                    }])
                ->contain('AnalysisType')
                ->first();
        $news = [];
        $i = 0;
        foreach ($allNews['analysis_news'] as $val) {
            if ($i <= 5) {
                $i++;
                $news[] = $val['news'];
            }
        }
        
        return ['userName' => $user,
            'stockInfo' => $stockInfo,
            'symbol' => $analysis['company']['symbol'],
            'name' => $analysis['company']['name'],
            'news' => $news
        ];
    }

    public function analysisPrint()
    {
        $data = $this->dataPrint();
        $user_name = $data['userName'];
        $stockInfo = $data['stockInfo'];
        $company_name = $data['name'];
        $company_symbol = $data['symbol'];
        $news = $data['news'];
        
        $this->apiResponse['user_name'] = $user_name;        
        $this->apiResponse['stockInfo'] = $stockInfo;        
        $this->apiResponse['company_name'] = $company_name;      
        $this->apiResponse['company_symbol'] = $company_symbol;     
        $this->apiResponse['news'] = $news;   
        
        // $this->set(compact('user_name', 'stockInfo', 'company_name', 'company_symbol', 'news'));
        // $this->viewBuilder()->setLayout(false);
        // $html = $this->render('pdf1');
    }

    public function analysisWord()
    {
        $data = $this->dataPrint();
        $user_name = $data['userName'];
        $stockInfo = $data['stockInfo'];
        $company_name = $data['name'];
        $company_symbol = $data['symbol'];
        $news = $data['news'];
        $this->set(compact('user_name', 'stockInfo', 'company_name', 'company_symbol', 'news'));
        $this->viewBuilder()->setLayout(false);
        $this->render('word');

        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment; Filename=Analysis.doc");
        header("Pragma: no-cache");
        header("Expires: 0");

////        $doc->saveHTML();
    }

    public function shareTeam()
    {

        $request = $this->request->getData();
        $this->loadModel('Api.AppUsers');
        $this->loadModel('Api.Notifications');
        $this->loadModel('Api.Private');
        $this->loadModel('Api.UserInvite');
        $this->loadModel('Api.Messages');

        if (isset($this->request)) {
            $data = ['id' => $request['private_name'], 'user_id' => $request['private_user']];
            $invite = $this->UserInvite->setUserInvite($data);
            $country_id = $this->currentLanguage;

            $message = $this->Messages->newEntity();
            $message->message = $this->getSiteUrl() . $request['analysis_url'] . ' ' . $request['comment'];

            $message->user_id = $this->jwtPayload->id;
            $message->company_id = null;
            $message->trader_id = null;
            $message->country_id = $country_id;
            $message->private_id = $request['private_name'];
            $this->Messages->save($message);

            if ($invite) {
                $privateGet = $this->Private->get($request['private_name']);
                foreach ($request['private_user'] as $userId) {
                    $userData = $this->AppUsers->find()->where(['AppUsers.id' => $userId])->first();
                    $referer = $this->_getCurrentLanguage();
                    $url = '/' . $referer . '/private/' . $privateGet->slug;
                    $this->Notifications->user_id = $userId;
                    $this->Notifications->url = $url;
                    $this->Notifications->title = $this->Auth->user('username') . ' - ' . implode(' ', array_slice(explode(' ', 'Private ' . $request["comment"] . ' '), 0, 5)) . '...';
                    $bool = $this->Notifications->setNotification($this->Notifications);
                    try {
                        $email = new Email();

                        $email
                                ->template('sendNotification')
                                ->emailFormat('html')
                                ->to($userData['email'])
                                ->from('app@domain.com')
                                ->subject('Someone noticed you in a post')
                                ->viewVars(['email' => $userData['email'],
                                    'from_username' => $this->Auth->user()['username'],
                                    'to_username' => $userData['username'],
                                    'to_first_name' => $userData['first_name'],
                                    'to_last_name' => $userData['last_name'],
                                    'url' => $this->getSiteUrl() . '' . $url,
                                    'message' => implode(' ', array_slice(explode(' ', $request["comment"]), 0, 5)),
                                ])
                                ->send();
                    } catch (\Exception $e) {
                        
                    }
                }
                if ($bool) {
                    $this->Flash->success(__('The Share with Team has been saved.'));
                } else {
                    $this->Flash->error(__('The Share with Team could not be saved. Please, try again.'));
                }
            }
            return $this->redirect(['action' => 'all']);
        }
    }

    public function analysisType(){
        $this->loadModel('Api.AnalysisType');
         $type = $this->AnalysisType->find('all')->toArray();
         
         $this->apiResponse['type'] = $type; 
    }
}