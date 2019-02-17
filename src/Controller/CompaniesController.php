<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class CompaniesController extends AppController
{

    const JMD = 'JMD';
    const USD = 'USD';

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
     * get Company info and set variable userId based on logged-in user
     *
     * @param $symbol string company symbol
     * @return mixed
     */
    public function symbol($symbol)
    {

        if (empty($symbol)) {
            return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
        }

        $currentLanguage = $this->_getCurrentLanguage();

        $companyInfo = $this->Companies->getCompanyInfo($symbol, $currentLanguage);
        $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);

        if (empty($companyInfo)) {
            if ($currentLanguage != self::JMD) {
                $companyInfo = $this->Companies->getCompanyInfo($symbol, self::JMD);
                if (empty($companyInfo)) {
                    return $this->redirect(['_name' => 'home']);
                }
                $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);
            } elseif ($this->_getCurrentLanguage() != self::USD) {
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

        $this->loadModel('Stocks');
        if ($currentLanguage == self::JMD) {
            $topStocks = $this->Stocks->getStockTopPerformInformation($this->_getCurrentLanguage());
            $worstStocks = $this->Stocks->getStockWorstPerformInformation($this->_getCurrentLanguage());
            $stockInfo = $this->Stocks->getStockInformationLocal($companyInfo['symbol'], $companyInfo['id']);
        } else {
            $topStocks = $this->Stocks->getStockTopPerformInformation($this->_getCurrentLanguage());
            $worstStocks = $this->Stocks->getStockWorstPerformInformation($this->_getCurrentLanguage());
            $stockInfo = $this->Stocks->getStockInformation($companyInfo['symbol']);
        }

        $this->loadModel('News');
        $news = $this->News->getCompanyNews($symbol, $currentLanguage, $companyInfo['name']);

        $userId = $this->Auth->user('id');

        if ($this->Auth->user()) {
            $this->loadModel('Messages');
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

        $this->loadModel('WatchlistGroup');

        $stockWatchlists = $this->WatchlistGroup->getWatchlists($userId);

        $watchlistStock = $this->WatchlistGroup->getList($userId);

        $this->set(compact(
            'companyInfo',
            'userId',
            'messages',
            'stockInfo',
            'news',
            'avatarPath',
            'page_is',
            'page_data',
            'stockWatchlists',
            'watchlistStock',
            'topStocks',
            'worstStocks'
        ));

        $this->set('_serialize', ['companyInfo']);
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
            return $this->redirect(['_name' => 'home']);
        }

        $companyInfo = $this->Companies->getCompanyInfo($symbol, $this->_getCurrentLanguage());
        $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);

        if (empty($companyInfo)) {
            if ($this->_getCurrentLanguage() != self::JMD) {
                $companyInfo = $this->Companies->getCompanyInfo($symbol, self::JMD);
                if (empty($companyInfo)) {
                    return $this->redirect(['_name' => 'home']);
                }
                $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);
            } elseif ($this->_getCurrentLanguage() != self::USD) {
                $companyInfo = $this->Companies->getCompanyInfo($symbol, self::USD);
                if (empty($companyInfo)) {
                    return $this->redirect(['_name' => 'home']);
                }
                $company_market = $this->Companies->getCompanyMarket($companyInfo['exchange_id']);
            } else {
                return $this->redirect(['_name' => 'home']);
            }
        }
        if ($company_market != $this->_getCurrentLanguage()) {
            return $this->redirect($company_market . '/symbol/' . $symbol);
        }

        $this->loadModel('Stocks');

        $options = $this->Stocks->getStockOptions($companyInfo['symbol'], $companyInfo['id']);

        $this->loadModel('News');
        $news = $this->News->getCompanyNews($symbol, $this->_getCurrentLanguage(), $companyInfo['name'], 2);

        $this->loadModel('Messages');

        $userId = $this->Auth->user('id');
        $currentLanguage = $this->_getCurrentLanguageId();

        $avatarPath = '';
        if ($this->Auth->user()) {
            $avatarPath = $this->Messages->AppUsers->get($this->Auth->user('id'))->avatarPath;
            if ($avatarPath == Configure::read('Users.Avatar.placeholder')) {
                $avatarPath = Configure::read('Users.avatar.default');
            }
        }

        $this->loadModel('WatchlistGroup');
        $stockWatchlists = $this->WatchlistGroup->getWatchlists($userId);

        ///this is for ajax///

        $page_is = 'company';
        $page_data = $companyInfo['id'];

        //////////////////////
        $this->set(compact('companyInfo', 'userId', 'options', 'news', 'currentLanguage', 'avatarPath', 'page_is', 'page_data', 'stockWatchlists'));
        $this->set('_serialize', ['companyInfo']);
    }

    public function search()
    {
        $this->loadModel('SearchSummary');
        if ($this->request->is('ajax')) {
            $data = $this->SearchSummary->search($this->request->query);
            $users = !empty($data['user']) ? $data['user'] : [];
            $companies = !empty($data['company']) ? $data['company'] : [];
            $trader = !empty($data['trader']) ? $data['trader'] : [];

            echo json_encode([
                'users' => $users,
                'companies' => $companies,
                'trader' => $trader
            ]);
            exit;
        } else {
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
            $data = $this->SearchSummary->formatSearch($pData);
            $params['phrase'] = $this->request->query['q'];

            $users = !empty($data['user']) ? $data['user'] : [];
            $companies = !empty($data['company']) ? $data['company'] : [];
            $trader = !empty($data['trader']) ? $data['trader'] : [];
            $this->set('params', $params);
            $this->set(compact('users', 'companies', 'trader'));
        }
    }

    /**
     * Gets the symbol to use at mention.js
     *
     * @return void
     */
    public function getMentionSymbols()
    {
        $companies = $this->Companies->getMentionSymbols($this->_getCurrentLanguage());
        $mentionSymbols = [];
        foreach ($companies as $key => $company) {
            $mentionSymbols[] = [
                'name' => $company->name,
                'username' => $company->symbol,
            ];
        }

        $mentionSymbols = array_values($mentionSymbols);

        echo json_encode([
            'mentionSymbols' => $mentionSymbols
        ]);
        exit;
    }

    /**
     * Gets the stocks info to show in watchlist
     *
     * @return void
     */
    public function getStocksInfo()
    {
        $response = $this->Companies->getStocksInfo($this->request->getData('data'), $this->_getCurrentLanguage());
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function stocksList()
    {
        $this->loadModel('StocksDetails');

        $this->set('exchanges', []);
        $this->set('indexes', $this->Companies->exchanges->find('list')->where(['country_id'=>$this->_getCurrentLanguageId()]));
        $this->set('sectors', $this->Companies->find('list', ['keyField' => 'sector','valueField' => 'sector'])->group(['Companies.sector']));
        $this->set('industries', $this->Companies->find('list', ['keyField' => 'industry','valueField' => 'industry'])->group(['Companies.industry']));
        $this->set('countries', $this->Countries->find('list'));
    }

    public function getEvents()
    {
        $this->loadModel('Events');
        $date = $this->request->getQuery('data');
        $events = $this->Events->getCalendar($date, $this->_getCurrentLanguage());
        $this->set(compact('events'));
        return $this->render('get_events_ajax', 'ajax');
    }

    public function ajaxManageCompanySearch()
    {
        $requestData = $this->request->getQuery();

        $obj = new \App\Model\DataTable\CompanyFrontDataTable();
        $result = $obj->ajaxManageCompanySearch($requestData, $this->_getCurrentLanguage());

        echo $result;
        exit;
    }

    // public function stocktmp()
    // {
    //     $language = $this->_getCurrentLanguage();
    //     $requestData = $this->request->getQuery();

    //     $companies = $this->Companies->find('all')
    //             ->contain(['Exchanges' => function ($q) use ($language)
    //         {
    //             return $q->autoFields(false)
    //                     ->contain(['Countries' => function ($q) use ($language)
    //                         {
    //                             return $q->autoFields(false)
    //                                     ->where(['Countries.market' => $language]);
    //                         }]);
    //         }]);

    //     if (!empty($requestData)) {
    //         if (isset($requestData['symbol']) && !empty($requestData['symbol'])) {
    //             $companies->where(['Companies.symbol' => $requestData['symbol']]);
    //         }

    //         if (isset($requestData['limit']) && is_numeric($requestData['limit'])) {
    //             $companies->limit($requestData['limit']);
    //             if (isset($requestData['page']) && is_numeric($requestData['page'])) {
    //                 $companies->page($requestData['page']);
    //             }
    //         }
    //     }

    //     foreach ($companies as $row) {
    //         if ($language == self::JMD) {
    //             $stockInfo = $this->Companies->Stocks->getStockInformationLocal($row["symbol"], $row['id']);
    //         } else {
    //             $stockInfo = $this->Companies->Stocks->getStockInformation($row["symbol"]);
    //         }

    //         $price = $stockInfo['info']['1. open'] - $stockInfo['info']['4. close'];

    //         $change = 0;

    //         if ($stockInfo['info']['1. open'] > 0) {
    //             $change = (($price) * 100 / $stockInfo['info']['1. open']);
    //         }

    //         $change = number_format($change, 2, '.', '');
    //         $nestedData['id'] = $row["id"];
    //         $nestedData['symbol'] = $row["symbol"];
    //         $nestedData['company_name'] = $row["name"];
    //         $nestedData['price'] = '$' . number_format($stockInfo['info']['1. open'], 2, '.', ' ');
    //         $nestedData['change'] = $change . '%';
    //         $nestedData['vol'] = $stockInfo['info']['5. volume'];
    //         $nestedData['index'] = $row["exchange"]["name"];
    //         $nestedData['sector'] = $row["sector"];
    //         $response[] = $nestedData;
    //     }

    //     $this->set(compact('response'));
    //     $this->set('_serialize', ['response']);
    //     $this->response->header('Access-Control-Allow-Origin: *');
    // }

}
