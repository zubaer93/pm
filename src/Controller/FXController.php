<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * Exchanges Controller
 *
 * @property \App\Model\Table\ExchangesTable $Exchanges
 *
 * @method \App\Model\Entity\Exchange[] paginate($object = null, array $settings = [])
 */
class FXController extends AppController
{
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
        $this->loadComponent('Csrf');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->loadModel('Trader');
        $trader = $this->Trader->getTrader();
        $this->loadModel('WatchlistForexItems');
        $watchlistItems = $this->WatchlistForexItems->find()
            ->where(['user_id' => $this->Auth->user('id')]);
        $this->set(compact('trader', 'watchlistItems'));
        $this->set('_serialize', ['trader', 'watchlistItems']);
    }

    public function getTraderJs()
    {
        $this->loadModel('Trader');
        $trader = $this->Trader->getTrader();
        $response = [
            'trader' => $trader
        ];
        $this->setJsonResponse($response);
        return $this->response;
    }

    protected function setJsonResponse($response)
    {
        $response = json_encode($response);
        $this->response->type('application/json');
        $this->response->body($response);
    }

    public function symbol($currency)
    {
        if (empty($currency)) {
            return $this->redirect(['_name' => 'forex_currency']);
        }


        $this->loadModel('Trader');

        $exchangeInfo = $this->Trader->__getTraderInfoFromCurrency($currency);
        $this->loadModel('News');
        $news = $this->News->getTraderNews($exchangeInfo);

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

        $page_is = 'trader';
        $page_data = $currency;

        //////////////////////

        $this->set(compact('exchangeInfo', 'userId','currentLanguage', 'news', 'avatarPath', 'page_is', 'page_data'));
        $this->set('_serialize', ['exchangeInfo']);
    }

}
