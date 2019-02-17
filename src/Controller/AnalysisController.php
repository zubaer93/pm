<?php

namespace App\Controller;

use App\Model\Table\PrivateTable;
use Cake\Core\Configure;
use Cake\Mailer\Email;
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

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->components()->unload('Csrf');
    }

    /**
     * 
     * @param type $stock
     * @return type
     */
    public function index($stock, $coming = false)
    {
        if (empty($this->Auth->user()) && $coming == 'events') {
            $this->request->session()->write('event_redirect', $this->request->here);
            $this->redirect('/login');
        }
        $symbol = $stock;
        $stockTopInfo = [];
        $stockWorstInfo = [];
        $option = [];
        $userId = $this->Auth->user('id');

        $language = $currentLanguage = $this->_getCurrentLanguage();

        if (empty($symbol)) {
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        $this->loadModel('Stocks');
        $this->loadModel('Events');
        $this->loadModel('SectorPerformances');
        $this->loadModel('FinancialStatement');
        $this->loadModel('BuySellBroker');

        $avatarPath = '';
        if ($this->Auth->user()) {
            $avatarPath = $this->Analysis->AppUsers->get($this->Auth->user('id'))->avatarPath;
            if ($avatarPath == Configure::read('Users.Avatar.placeholder')) {
                $avatarPath = Configure::read('Users.avatar.default');
            }
        }

        $companyInfo = $this->Analysis->Companies->getCompanyInfo($symbol, $language);

        $company_market = $this->Analysis->Companies->getCompanyMarket($companyInfo['exchange_id']);

        if (empty($companyInfo)) {
            if ($language != self::JMD) {
                $companyInfo = $this->Analysis->Companies->getCompanyInfo($symbol, self::JMD);
                if (empty($companyInfo)) {
                    return $this->redirect(['_name' => 'home']);
                }
                $company_market = $this->Analysis->Companies->getCompanyMarket($companyInfo['exchange_id']);
            } elseif ($language != self::USD) {
                $companyInfo = $this->Analysis->Companies->getCompanyInfo($symbol, self::USD);

                if (empty($companyInfo)) {
                    return $this->redirect(['_name' => 'home']);
                }
                $company_market = $this->Analysis->Companies->getCompanyMarket($companyInfo['exchange_id']);
            } else {
                return $this->redirect(['_name' => 'home']);
            }
        }
        if ($company_market != $language) {
            return $this->redirect($company_market . '/analysis/' . $symbol);
        }

        if ($language == self::JMD) {
            $stockInfo = $this->Stocks->getStockInformationLocal($symbol, $companyInfo['id']);
            $events = $this->Events->getEvent($companyInfo['id']);

            $option = $this->Stocks->getStockMarketDepth($symbol, $language, $companyInfo['id'], $stockInfo['info']['1. open'], (isset($stockInfo['info']['7.eps']) ? $stockInfo['info']['7.eps'] : ''));

        } else {
            $stockInfo = $this->Stocks->getStockInformation($symbol);
            $events = null;
        }

        /** required * */
        $page_is = 'company';
        $page_data = $companyInfo['id'];
        /** required * */
        $news = $this->Analysis->AnalysisNews->News->getCompanyNews($symbol, $language, $companyInfo['name']);
        $news_array = [];

        foreach ($news as $val) {
            $news_array[$val['id']] = $val['title'];
        }

        $sector = array_change_key_case($this->SectorPerformances->getSectorPerformances($language), CASE_UPPER);

        $sector_change = null;

        if (array_key_exists(strtoupper($companyInfo['sector']), $sector)) {
            $sector_change = $sector[strtoupper($companyInfo['sector'])];
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
            ->orderDesc('BuySellBroker.created_at');

        $this->loadModel('WatchlistGroup');
        $watchlistStock = $this->WatchlistGroup->getList($userId);

        $this->set(compact(
            'companyInfo',
            'sector_change',
            'userId',
            'news',
            'brokers_details',
            'option',
            'news_array',
            'stockInfo',
            'currentLanguage',
            'avatarPath',
            'page_is',
            'page_data',
            'stock',
            'analysis',
            'statement',
            'events',
            'watchlistStock'
        ));

        $this->set('_serialize', ['analysis']);
    }

    /**
     * analysisDetail method it will show the detail page
     * 
     * @param string $key Analysis key
     * @return void
     */
    public function analysisDetail($key)
    {
        if (!$this->Auth->user()) {
            return $this->redirect(['_name' => 'home']);
        }

        $userId = $this->Auth->user('id');
        $analysis = $this->Analysis->findAnalysi($key);

        $companyInfo = $this->Analysis->Companies->get($analysis->company_id);
        $companyMarket = $this->Analysis->Companies->getCompanyMarket($companyInfo->exchange_id);

        $this->loadModel('Stocks');
        if ($this->_getCurrentLanguage() == self::JMD) {
            $stockInfo = $this->Stocks->getStockInformationLocal($companyInfo->symbol, $companyInfo->id);
        } else {
            $stockInfo = $this->Stocks->getStockInformation($companyInfo['symbol']);
        }

        $language = $this->_getCurrentLanguage();
        $symbol = $companyInfo->symbol;
        $this->loadModel('FinancialStatement');
        $statement = $this->FinancialStatement->getFinancialStatements($symbol, $language);

        $this->loadModel('WatchlistGroup');
        $stockWatchlists = $this->WatchlistGroup->getWatchlists($userId);

        $this->set(compact('analysis', 'stockInfo', 'companyInfo', 'companyMarket', 'statement', 'stockWatchlists'));
    }

    /**
     * 
     * @param type $symbol
     */
    public function sectorModal($symbol)
    {
        $this->loadModel('Stocks');
        $companyInfo = $this->Analysis->Companies->getCompanyInfo($symbol, $this->_getCurrentLanguage());
        $sector = $this->Analysis->Companies->getCompanisBySector($companyInfo['sector'], $this->_getCurrentLanguage(), 5);

        $language = $this->_getCurrentLanguage();
        $options = [];
        foreach ($sector as $val) {
            $companyInfo = $this->Analysis->Companies->getCompanyInfo($val['symbol'], $language);
            $stockInfo = $this->Stocks->getStockInformationLocal($val['symbol'], $companyInfo['id']);
            $options[$companyInfo['id']]['companyInfo'] = $companyInfo;
            $options[$companyInfo['id']]['stockDetail'] = $this->Stocks->getStockMarketDepth($symbol, $language, $companyInfo['id'], $stockInfo['info']['1. open'], (isset($stockInfo['info']['7.eps']) ? $stockInfo['info']['7.eps'] : ''));
        }

        $this->set(compact('options', 'symbol'));
        $this->render('Partial/sector_modal', 'ajax');
    }

    /**
     * 
     * @param type $symbol
     */
    public function sector($symbol)
    {
        $companyInfo = $this->Analysis->Companies->getCompanyInfo($symbol, $this->_getCurrentLanguage());
        $sector = $this->Analysis->Companies->getCompanisBySector($companyInfo['sector'], $this->_getCurrentLanguage(), 20);

        $this->set(compact('sector'));
        $this->set('_serialize', ['sector']);
    }

    /**
     * 
     * @param type $symbol
     */
    public function industry($symbol)
    {
        $companyInfo = $this->Analysis->Companies->getCompanyInfo($symbol, $this->_getCurrentLanguage());

        $sector = $this->Analysis->Companies->getCompaniesByIndustry($companyInfo['industry'], $this->_getCurrentLanguage());

        $this->set(compact('sector'));
        $this->set('_serialize', ['sector']);
    }

    /**
     * 
     * @param type $symbol
     * @return type
     */
    public function getChartData($symbol = null)
    {
        $this->loadModel('Stocks');
        $companyInfo = $this->Analysis->Companies->getCompanyInfo($symbol, $this->_getCurrentLanguage());

        $stockInfo = $this->Stocks->getStockInformationChart($companyInfo['symbol'], $companyInfo['id']);
        $response = [
            'status' => 'success',
            'stockInfo' => $stockInfo,
            'message' => 'Successful!'
        ];
        $this->response->statusCode(200);
        $this->setJsonResponse($response);
        return $this->response;
    }

    /**
     * All method
     *
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function all()
    {
        if (!$this->Auth->user()) {
            return $this->redirect(['_name' => 'home']);
        }
        $userId = $this->Auth->user('id');
        $language = $this->_getCurrentLanguage();
        $allAnalysis = $this->paginate($this->Analysis->find('all')
            ->where(['user_id' => $userId])
            ->contain([
                'AnalysisNews',
                'AnalysisTags',
                'AnalysisSymbols',
                'AnalysisWatchList',
                'AnalysisType',
                'Companies' => [
                    'Exchanges' => [
                        'Countries'
                    ]
                ]
            ])
        );

        $exchange = '';
//        if (isset($companyInfo['exchange']['name'])) {
//            $exchange = $companyInfo['exchange']['name'] . ':';
//        }

        $this->loadModel('News');
        $news = $this->News->getNews($language, false);
        $newsList = Hash::combine($news, '{n}.id', '{n}.title');

        $userId = $this->Auth->user('id');
        
        $this->loadModel('Follow');
        $following = $this->Follow->find('all')
            ->contain('Following')
            ->where(['Follow.user_id' => $userId])
            ->order(['Follow.created_at' => 'desc']);

        $followings = Hash::extract($following->toArray(), '{n}.following.id');

        $followings = ['98b04a17-502f-46ec-a624-3e34894a7e39'];
        $allUsers = [];
        if (count($followings)) {
            $this->loadModel('AppUsers');
            $allUsers = $this->AppUsers->find('list', [
                'keyField' => 'id',
                'valueField' => 'username'
            ])->where(['id IN' => $followings]);
        }

        $this->loadModel('Private');
        $private = $this->Private->find('list')
            ->where(['Private.user_id' => $userId]);

        $this->loadModel('WatchlistGroup');
        $watchlistStock = $this->WatchlistGroup->getList($userId);

        $this->set(compact('allAnalysis', 'watchlistStock', 'newsList', 'allUsers', 'private'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($symbol)
    {
        $requsetData = $this->request->getData();
        return $this->addAnalysis($requsetData, $symbol);
    }

    /**
     * 
     */
    public function addModal()
    {
        $requsetData = $this->request->getData();
        return $this->addAnalysis($requsetData, $requsetData['symbol']);
    }

    /**
     * 
     * @param type $requsetData
     * @param type $symbol
     * @return type
     */
    private function addAnalysis($requsetData, $symbol)
    {
        $companyInfo = $this->Analysis->Companies->getCompanyInfo($symbol, $this->_getCurrentLanguage());
        $userId = $this->Auth->user('id');
        $entity = $this->Analysis->find('all')
                ->where(['user_id' => $userId])
                ->where(['company_id' => $companyInfo['id']])
                ->first();
        if (!isset($requsetData['row_id'])) {
            $requsetData['company_id'] = $companyInfo['id'];
            $requsetData['user_id'] = $userId;
            $analysi = $this->Analysis->add($requsetData);

            if ($analysi) {
                $this->loadModel('AnalysisNews');
                $this->AnalysisNews->add($requsetData['news'], $analysi['id']);

                $this->loadModel('AnalysisSymbols');
                $this->AnalysisSymbols->add($requsetData['symbol'], $analysi['id']);

                $this->loadModel('AnalysisTags');
                $this->AnalysisTags->add($requsetData['tags'], $analysi['id']);

                $this->loadModel('AnalysisWatchList');
                $this->AnalysisWatchList->add($requsetData['watch_list'], $analysi['id']);

                $bool = true;
            } else {
                $bool = false;
            }
            if ($bool) {
                $this->Flash->success(__('The analysis has been saved.'));
            } else {
                $this->Flash->error(__('The analysis could not be saved. Please, try again.'));
            }
        } else {
            $analysi = $this->Analysis->get($requsetData['row_id'], [
                'contain' => []
            ]);
            $requsetData['company_id'] = $companyInfo['id'];
            $requsetData['user_id'] = $userId;

            $analysi = $this->Analysis->patchEntity($analysi, $requsetData);

            if ($this->Analysis->save($analysi)) {

                $this->loadModel('AnalysisNews');
                $this->AnalysisNews->add($requsetData['news'], $analysi['id']);

                $this->loadModel('AnalysisSymbols');
                $this->AnalysisSymbols->add($requsetData['symbol'], $analysi['id']);

                $this->loadModel('AnalysisTags');
                $this->AnalysisTags->add($requsetData['tags'], $analysi['id']);

                $this->loadModel('AnalysisWatchList');
                $this->AnalysisWatchList->add($requsetData['watch_list'], $analysi['id']);

                $bool = true;
            } else {
                $bool = false;
            }
            if ($bool) {
                $this->Flash->success(__('The analysis has been edited.'));
            } else {
                $this->Flash->error(__('The analysis could not be edited. Please, try again.'));
            }
        }
        try {

            return $this->redirect(['controller' => 'Analysis', 'action' => 'all']);
        } catch (\Exception $e) {
            return $this->redirect(['_name' => 'home']);
        }
    }

    protected function setJsonResponse($response)
    {
        $response = json_encode($response);
        $this->response->type('application/json');
        $this->response->body($response);
    }

    /**
     * Edit method
     *
     * @param string|null $id Analysis id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $analysi = $this->Analysis->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $analysi = $this->Analysis->patchEntity($analysi, $this->request->getData());
            if ($this->Analysis->save($analysi)) {
                $this->Flash->success(__('The analysi has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The analysi could not be saved. Please, try again.'));
        }
        $users = $this->Analysis->Users->find('list', ['limit' => 200]);
        $companies = $this->Analysis->Companies->find('list', ['limit' => 200]);
        $this->set(compact('analysi', 'users', 'companies'));
        $this->set('_serialize', ['analysi']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Analysis id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $requestData = $this->request->getQuery('data');

        $bool = true;
        $array = [];
        if (!$this->Auth->user()) {
            $bool = false;
        } else {
            $userId = $this->Auth->user('id');

            foreach ($requestData as $data) {
                $analysis = $this->Analysis->find()
                        ->where(['user_id' => $userId])
                        ->where(['id' => $data])
                        ->first();

                if (!$this->Analysis->delete($analysis)) {
                    $bool = false;
                } else {
                    $array[] = $data;
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

    public function analysisEditPartial()
    {
        $this->autoRender = false;

        $requestData = $this->request->getQuery('data');
        if (!$this->Auth->user()) {
            $bool = false;
        } else {
            $userId = $this->Auth->user('id');
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
            $this->loadModel('Stocks');
            if ($this->_getCurrentLanguage() == self::JMD) {
                $stockInfo = $this->Stocks->getStockInformationLocal($companyInfo['symbol'], $companyInfo['id']);
            } else {
                $stockInfo = $this->Stocks->getStockInformation($companyInfo['symbol']);
            }

            $watchlistListOBJ = new WatchlistController();
            $watchlistList = $watchlistListOBJ->getAllGroup();
            $watchlistList_array = [];
            foreach ($watchlistList as $val) {
                $watchlistList_array[$val['id']] = $val['name'];
            }

            $this->loadModel('News');
            $news = $this->News->getCompanyNews($companyInfo['symbol'], $this->_getCurrentLanguage(), $companyInfo['name']);
            $news_array = [];
            foreach ($news as $val) {
                $news_array[$val['id']] = $val['title'];
            }

            $this->set(compact(
                            'analysis', 'stockInfo', 'news_array'
                            , 'watchlistList_array', 'companyInfo', 'companyMarket', 'requestData'
            ));

            $this->set(
                    '_serialize'
                    , ['analysis']
            );
            $this->render('Partial/edit_partial_all', 'ajax');
        }
    }

    /**
     * 
     */
    protected function dataPrint()
    {
        if (!$this->Auth->user()) {
            return $this->redirect(['_name' => 'home']);
        }
        $user = $this->Auth->user('first_name');
        $this->loadModel('Stocks');
        $id = $this->request->getQuery('print');
        $analysis = $this->Analysis->find('all')
                ->where(['Analysis.id' => $id])
                ->contain('Companies')
                ->first();
        $this->loadModel('Stocks');
        $companyMarket = $this->Analysis->Companies->getCompanyMarket($analysis['company']['exchange_id']);

        if (self::JMD == $companyMarket) {
            $stockInfo = $this->Stocks->getStockInformationLocal($analysis['company']['symbol'], $analysis['company']['id']);
        } else {
            $stockInfo = $this->Stocks->getStockInformation($analysis['company']['symbol']);
        }

        $userId = $this->Auth->user('id');
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
        $this->set(compact('user_name', 'stockInfo', 'company_name', 'company_symbol', 'news'));
        $this->viewBuilder()->setLayout(false);
        $html = $this->render('pdf1');
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

    public function analysisApprove()
    {
        $requestData = $this->request->getQuery('data');
        if (!$this->Auth->user()) {
            $bool = false;
        } else {
            $userId = $this->Auth->user('id');
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
                    $response = [
                        'status' => 'success',
                        'message' => 'Successful!',
                        'approve' => $array['approve'],
                    ];
                    $this->response->statusCode(200);
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Error!'
                    ];
                    $this->response->statusCode(500);
                }
                $this->setJsonResponse($response);

                return $this->response;
            }
        }
    }

    /**
     * 
     */
    public function ajaxManageAnalysisTimeAndSalesSearch()
    {
        $requestData = $this->request->getQuery();

        $obj = new \App\Model\DataTable\AnalysisDataTable();
        $result = $obj->ajaxManageAnalysisTimeAndSalesSearch($requestData, $this->_getCurrentLanguage());

        echo $result;
        exit;
    }

    public function makeCopy()
    {
        $id = $this->request->getQuery('data');
        $userId = $this->Auth->user('id');
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
        $data = [
            'text' => $allNews->text,
            'files' => '',
            'name' => $allNews->name,
            'type' => $allNews->type,
            'watch_list' => $watch_list,
            'news' => $news,
            'tags' => $allNews['analysis_tags'][0]->name
        ];

        return $this->addAnalysis($data, $companyInfo->symbol);
    }

    public function shareTeam()
    {
        $request = $this->request->getData();
        $this->loadModel('AppUsers');
        $this->loadModel('Notifications');
        $this->loadModel('Private');
        $this->loadModel('UserInvite');
        $this->loadModel('Messages');

        if (isset($this->request)) {
            $data = ['id' => $request['private_name'], 'user_id' => $request['private_user']];
            $invite = $this->UserInvite->setUserInvite($data);
            $country_id = $this->_getCurrentLanguageId();

            $message = $this->Messages->newEntity();
            $message->message = $this->getSiteUrl() . $request['analysis_url'] . ' ' . $request['comment'];

            $message->user_id = $this->Auth->user('id');
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

}
