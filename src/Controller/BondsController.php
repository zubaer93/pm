<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Client;

class BondsController extends AppController
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
        $allBonds = $this->Bonds->getBondsBondeValue();
        $corporateBonds = $this->Bonds->getCorporateBonds($this->_getCurrentLanguage());

        $this->loadModel('WatchlistBondItems');
        $watchlistItems = $this->WatchlistBondItems->find()
            ->where(['user_id' => $this->Auth->user('id')]);

        $this->set(compact('allBonds', 'corporateBonds', 'watchlistItems'));
    }

    /**
     * historicalPrice method It will show the historical prices.
     *
     * @param string $isinCode ISIN Code to find the historical
     * @return \Cake\Http\Response|void
     */
    public function historicalPrice($isinCode)
    {
        $bonds = $this->Bonds->getHistorical($isinCode, $this->Auth->user('id'), $this->_getCurrentLanguage());
        $this->loadModel('WatchlistBondItems');
        $hasItem = $this->WatchlistBondItems->find()
            ->where([
                'WatchlistBondItems.user_id' => $this->Auth->user('id'),
                'WatchlistBondItems.isin_code' => $isinCode
            ])->count();

        $isinCode = $bonds['isinCode'];
        $messages = $bonds['messages'];
        $bond = $bonds['bond'];
        $news = $bonds['news'];
        $name = $bonds['name'];
        $price = $bonds['price'];
        $userId = $this->Auth->user('id');
        $currentLanguage = $this->Auth->user('id');

        $this->set(compact('isinCode', 'messages', 'bond', 'news', 'name', 'price', 'userId', 'currentLanguage', 'hasItem'));

    }
}