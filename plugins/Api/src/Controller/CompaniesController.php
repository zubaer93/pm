<?php

namespace Api\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;

class CompaniesController extends AppController
{

    const JMD = 'JMD';
    const USD = 'USD';

    public function initialize()
    {
        parent::initialize();
    }

    public function symbol($symbol)
    {
        $this->add_model(array('Api.Companies'));
        if (empty($symbol)) {
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        $currentLanguage = $this->currentLanguage;
        try {
            $companyInfo = $this->Companies->getCompanyInfo($symbol, $currentLanguage);
            $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);
            if (empty($companyInfo)) {
                if ($currentLanguage != self::JMD) {
                    $companyInfo = $this->Companies->getCompanyInfo($symbol, self::JMD);
                    if (empty($companyInfo)) {
                        return $this->redirect(['_name' => 'home']);
                    }
                    $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);
                } elseif ($this->currentLanguage != self::USD) {
                    $companyInfo = $this->Companies->getCompanyInfo($symbol, self::USD);
                    if (empty($companyInfo)) {
                        return $this->redirect(['_name' => 'home']);
                    }
                    $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);
                } else {
                    return $this->redirect(['_name' => 'home']);
                }
            }

            if ($company_market != $currentLanguage) {
                return $this->redirect($company_market . '/symbol/' . $symbol);
            }
            $this->loadModel('News');
            $news = $this->News->getCompanyNews($symbol, $currentLanguage, $companyInfo['name']);
            $this->loadModel('Api.Stocks');
            if ($currentLanguage == self::JMD) {
                $topStocks = $this->Stocks->getStockTopPerformInformation($this->currentLanguage);
                $worstStocks = $this->Stocks->getStockWorstPerformInformation($this->currentLanguage);
                $stockInfo = $this->Stocks->getStockInformationLocal($companyInfo['symbol'], $companyInfo['id']);
            } else {
                $topStocks = $this->Stocks->getStockTopPerformInformation($this->currentLanguage);
                $worstStocks = $this->Stocks->getStockWorstPerformInformation($this->currentLanguage);
                $stockInfo = $this->Stocks->getStockInformation($companyInfo['symbol']);
            }
            //pr($stockInfo);exit;

            /*$userId = $this->Auth->user('id');

            if ($this->Auth->user()) {
                $this->loadModel('Messages');
                $avatarPath = $this->Messages->AppUsers->get($this->Auth->user('id'))->avatarPath;
                if ($avatarPath == Configure::read('Users.Avatar.placeholder')) {
                    $avatarPath = Configure::read('Users.avatar.default');
                }
            } else {
                $avatarPath = '';
            }*/

            ///this is for ajax///
            $page_is = 'company';
            $page_data = $companyInfo['id'];

            //////////////////////

            //$this->loadModel('WatchlistGroup');
            //$watchlist = $this->WatchlistGroup->find('list', array('fields' => array('name', 'id')))->where(['user_id' => $userId])->toArray();
            $this->apiResponse['companyInfo'] = $companyInfo;
            $this->apiResponse['stockInfo'] = $stockInfo;
            $this->apiResponse['news'] = $news;
            $this->apiResponse['topStocks'] = $topStocks;
            $this->apiResponse['worstStocks'] = $worstStocks;
        } catch (\Exception $e) {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = $e->getMessage();
        }
    }

    /**
     * get Company options and set variable userId based on logged-in user
     *
     * @param $symbol string company symbol
     * @return mixed
     */
    public function options($symbol)
    {
        if (empty($symbol)) {
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        $companyInfo = $this->Companies->getCompanyInfo($symbol, $this->currentLanguage);
        $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);

        if (empty($companyInfo)) {
            if ($this->currentLanguage != self::JMD) {
                $companyInfo = $this->Companies->getCompanyInfo($symbol, self::JMD);
                if (empty($companyInfo)) {
                    return $this->redirect(['_name' => 'home']);
                }
                $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);
            } elseif ($this->currentLanguage != self::USD) {
                $companyInfo = $this->Companies->getCompanyInfo($symbol, self::USD);
                if (empty($companyInfo)) {
                    return $this->redirect(['_name' => 'home']);
                }
                $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);
            } else {
                return $this->redirect(['_name' => 'home']);
            }
        }
        if ($company_market != $this->currentLanguage) {
            return $this->redirect($company_market . '/symbol/' . $symbol);
        }

        $this->loadModel('Stocks');

        $options = $this->Stocks->getStockOptions($companyInfo['symbol'], $companyInfo['id']);

        $this->loadModel('News');
        $news = $this->News->getCompanyNews($symbol, $this->currentLanguage, $companyInfo['name'], 2);

        $this->loadModel('Messages');

        $userId = $this->Auth->user('id');
        $currentLanguage = $this->_getCurrentLanguageId();

        if ($this->Auth->user()) {
            $avatarPath = $this->Messages->AppUsers->get($this->Auth->user('id'))->avatarPath;
            if ($avatarPath == Configure::read('Users.Avatar.placeholder')) {
                $avatarPath = Configure::read('Users.avatar.default');
            }
        } else {
            $avatarPath = '';
        }

        ///this is for ajax///

        $page_is = 'company';
        $page_data = $companyInfo['id'];

        //////////////////////
        $this->set(compact('companyInfo', 'userId', 'options', 'news', 'currentLanguage', 'avatarPath', 'page_is', 'page_data'));
        $this->set('_serialize', ['companyInfo']);
    }

    public function search()
    {
        $this->loadModel('Api.SearchSummary');
        $this->loadComponent('Paginator');
        $this->paginate = [
            'limit' => 50,
            'order' => [
                'content_type' => 'asc'
            ]
        ];
        $query = $this->SearchSummary->find()
            ->where(['search_tag LIKE' => '%' . $this->request->query['q'] . '%']);
        $pData = $this->paginate($query)->toArray();
        $data = $this->formatSearch($pData);
        $params['phrase'] = $this->request->query['q'];
        if (!empty($data)) {
            $this->apiResponse['data'] = $data;
        } else {
            $this->apiResponse['data'] = [];
        }
    }

    public function optionApi($symbol)
    {
        $this->add_model(['Stocks']);
        try {
            $companyInfo = $this->Companies->getCompanyInfo($symbol, $this->currentLanguage);
            $options = $this->Stocks->getStockOptions($companyInfo['symbol'], $companyInfo['id']);
            $this->apiResponse['data'] = $options;
        } catch (\Exception $e) {
            $this->apiResponse['error'] = $e->getMessage();
        }

    }

    /**
     * Gets the symbol to use at mention.js
     *
     * @return void
     */
    public function getMentionSymbols()
    {
        $companies = $this->Companies->getMentionSymbols($this->currentLanguage);
        $mentionSymbols = [];
        foreach ($companies as $key => $company) {
            $mentionSymbols[] = [
                'name' => $company->name,
                'symbol' => $company->symbol,
            ];
        }
        $mentionSymbols = array_values($mentionSymbols);
        
        if ($mentionSymbols) {
            $this->apiResponse['data'] = $mentionSymbols;
        } else {
            $this->apiResponse['error'] = 'Info Not Found';
        }
    }

    /**
     * Gets the stocks info to show in watchlist
     *
     * @return void
     */
    public function getStocksInfo()
    {
        $this->add_model(array('Api.Companies'));
        $response = $this->Companies->getStocksInfo($this->request->getData('data'), $this->currentLanguage);
        if ($response) {
            $this->apiResponse['data'] = $response;
        } else {
            $this->apiResponse['error'] = 'Info Not Found';
        }


    }

    public function stocksList()
    {
        $this->loadModel('StocksDetails');

        $this->set('exchanges', []);
        $this->set('indexes', $this->Companies->exchanges->find('list')->where(['country_id' => $this->_getCurrentLanguageId()]));
        $this->set('sectors', $this->Companies->find('list', ['keyField' => 'sector', 'valueField' => 'sector'])->group(['Companies.sector']));
        $this->set('industries', $this->Companies->find('list', ['keyField' => 'industry', 'valueField' => 'industry'])->group(['Companies.industry']));

        $this->set('countries', $this->Countries->find('list'));


    }

    public function getEvents()
    {
        $this->loadModel('Api.Events');
        $date = $this->request->getQuery('data');
        if (!empty($date)) {
            $events = $this->Events->getCalendar($date);
        } else {
            $this->httpStatusCode = 400;// bad request
            $this->apiResponse['error'] = 'insert query parameter data';
            return;
        }
        if (!empty($events)) {
            $this->apiResponse['data'] = $events;
        } else {
            $this->apiResponse['data'] = [];
        }
    }

    public function ajaxManageCompanySearch()
    {
        $requestData = $this->request->getQuery();

        $obj = new \App\Model\DataTable\CompanyFrontDataTable();
        $result = $obj->ajaxManageCompanySearch($requestData, $this->_getCurrentLanguage());

        echo $result;
        exit;
    }

    public function stocktmp()
    {
        $language = $this->_getCurrentLanguage();
        $requestData = $this->request->getQuery();

        $companies = $this->Companies->find('all')
            ->contain(['Exchanges' => function ($q) use ($language) {
                return $q->autoFields(false)
                    ->contain(['Countries' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => $language]);
                    }]);
            }]);

        if (!empty($requestData)) {
            if (isset($requestData['symbol']) && !empty($requestData['symbol'])) {
                $companies->where(['Companies.symbol' => $requestData['symbol']]);
            }

            if (isset($requestData['limit']) && is_numeric($requestData['limit'])) {
                $companies->limit($requestData['limit']);
                if (isset($requestData['page']) && is_numeric($requestData['page'])) {
                    $companies->page($requestData['page']);
                }
            }
        }

        foreach ($companies as $row) {
            if ($language == self::JMD) {
                $stockInfo = $this->Companies->Stocks->getStockInformationLocal($row["symbol"], $row['id']);
            } else {
                $stockInfo = $this->Companies->Stocks->getStockInformation($row["symbol"]);
            }

            $price = $stockInfo['info']['1. open'] - $stockInfo['info']['4. close'];

            $change = 0;

            if ($stockInfo['info']['1. open'] > 0) {
                $change = (($price) * 100 / $stockInfo['info']['1. open']);
            }

            $change = number_format($change, 2, '.', '');
            $nestedData['id'] = $row["id"];
            $nestedData['symbol'] = $row["symbol"];
            $nestedData['company_name'] = $row["name"];
            $nestedData['price'] = '$' . number_format($stockInfo['info']['1. open'], 2, '.', ' ');
            $nestedData['change'] = $change . '%';
            $nestedData['vol'] = $stockInfo['info']['5. volume'];
            $nestedData['index'] = $row["exchange"]["name"];
            $nestedData['sector'] = $row["sector"];
            $response[] = $nestedData;
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
        $this->response->header('Access-Control-Allow-Origin: *');
    }

    public function index()
    {
        $this->loadModel('Api.Companies');
        try {
            $query = $this->Companies->getAllCompanyWithLang($this->currentLanguage);
            if ($query) {
                $this->apiResponse['data'] = $this->paginate($query);
                $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Companies'];
            }
        } catch (\Exception $e) {
            $this->apiResponse['error'] = $e->getMessage();
            $this->httpStatusCode = 404;
        }
        return;
    }

    public function view($symbol = null)
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.Companies'));
        if (!empty($symbol)) {
            if ($this->jwtPayload) {
                $company = $query = $this->Companies->getCompanyInfo($symbol, $this->currentLanguage, $this->jwtPayload->id);
            } else {
                $company = $query = $this->Companies->getCompanyInfo($symbol, $this->currentLanguage);
            }
            if (!empty($company)) {
                $this->apiResponse['data'] = $company;
            } else {
                throw new NotFoundException();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = "Please provide company symbol.";
        }
    }

    private function formatSearch($data)
    {
        $items = [];
        foreach ($data as $i => $item) {
            $params = json_decode($item->params, true);
            if ($item->content_type == 'company') {
                $items[] = [
                    'id' => $item['id'],
                    'type' => 'company',
                    'name' => $params['name'],
                    'symbol' => $params['symbol'],
                    'exchange' => $params['exchange'],
                    'url' => Router::url(['_name' => 'symbol', 'stock' => $params['symbol'], 'lang' => $params['lang']]),
                    'username' => '',
                    'icon' => '',
                    'from_currency_code' => '',
                    'to_currency_code' => ''
                ];
            } elseif ($item->content_type == 'trader') {
                $items[] = [
                    'id' => $item['id'],
                    'type' => 'trader',
                    'from_currency_code' => $params['from_currency_code'],
                    'to_currency_code' => $params['to_currency_code'],
                    'exchange' => $params['exchange'],
                    'name' => $params['to_currency_code'],
                    'username' => '',
                    'icon' => '',
                    'symbol' => $params['from_currency_code'],
                    'url' => '/USD/forex/symbol/' . $params['from_currency_code'] . '-' . $params['to_currency_code'],
                ];
            } else {
                $items[] = [
                    'id' => $item['id'],
                    'type' => 'user',
                    'name' => $params['first_name'] . ' ' . $params['last_name'],
                    'username' => $params['username'],
                    'icon' => '',
                    'from_currency_code' => '',
                    'to_currency_code' => '',
                    'exchange' => '',
                    'symbol' => '',
                    'url' => '/USD/user/' . $params['username']
                ];
            }
        }
        return $items;
    }

    // get trending companies
    public function trending()
    {
        $this->loadModel('Api.Companies');
        $trendingCompanies = $this->Companies->getTrendingCompanies($this->currentLanguage);
        if (!empty($trendingCompanies)) {
            $this->apiResponse['data'] = $trendingCompanies;
        } else {
            $this->apiResponse['data'] = [];
        }
    }

    // get trending countries
    public function country()
    {
        $this->loadModel('Api.Countries');
        $country = $this->Countries->getName($this->currentLanguage);
        if (!empty($country)) {
            $this->apiResponse['data'] = $country;
        } else {
            $this->apiResponse['data'] = [];
        }
    }

    // get sector performance
    public function sectorPerformance()
    {
        $this->request->allowMethod('get');
        $this->loadModel('Api.SectorPerformances');
        $sector = $this->SectorPerformances->getSectorPerformances($this->currentLanguage);
        $array = array_keys($sector);
        $array2 = array_values($sector);
        $sector = [];
        for ($i = 0; $i < count($array); $i++) {
            $a = substr($array2[$i], 0, -1);
            $sector[] = array('name' => $array[$i], 'percent' => $a);
        }
        if (!empty($sector)) {
            $this->apiResponse['data'] = $sector;
        } else {
            $this->apiResponse['data'] = [];
        }
    }

    // get all sector
    public function sectors()
    {
        $this->request->allowMethod('get');
        $this->loadModel('Api.Companies');
        $sectors_data = $this->Companies->find()->select(['sector'])->distinct(['sector'])->toArray();
        foreach ($sectors_data as $s) {
            if (!empty($s->sector)) {
                $sectors[] = array('sector' => $s->sector);
            }
        }
        if (!empty($sectors)) {
            $this->apiResponse['data'] = $sectors;
        } else {
            $this->apiResponse['data'] = [];
        }
    }
}
