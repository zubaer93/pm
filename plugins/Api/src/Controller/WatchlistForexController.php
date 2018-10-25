<?php

namespace Api\Controller;

use Api\Controller\AppController;

class WatchlistForexController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->add_model(array('Api.WatchlistForex'));
        $this->add_model(array('Api.WatchlistForexItems'));
        $this->add_model(array('Api.Trader'));

    }

    public function index()
    {
        $this->paginate = [
            'fields' => ['id' => 'WatchlistForexItems.id', 'user_id' => 'WatchlistForexItems.user_id',
                'trader_id' => 'WatchlistForexItems.trader_id', 'watchlist_forex_id' => 'WatchlistForexItems.watchlist_forex_id',
                'watchlist_forex_name' => 'WatchlistForex.name', 'created_at' => 'WatchlistForexItems.created'],
            'contain' => ['WatchlistForex'],
        ];
        $watchlistForex = $this->paginate($this->WatchlistForexItems->find()
            ->where(['WatchlistForexItems.user_id' => $this->jwtPayload->id])
            ->order(['WatchlistForexItems.created' => 'desc ']))
            ->toArray();
        foreach ($watchlistForex as $item) {
            $item['forex_info'] = $this->Trader->getTraderById($item->trader_id);
            if (!empty($item['forex_info']) && count($item['forex_info']) > 0) {
                $item['forex_info'] = $item['forex_info'][0];
            }
        }
        if (!empty($watchlistForex)) {
            $this->apiResponse['data'] = $watchlistForex;
        } else {
            $this->apiResponse['data'] = [];
        }
    }

    /**
     * add method it will add a new watchlist group.
     *
     * @return void
     */
    public function add()
    {
        $watchlistForex = $this->WatchlistForex->newEntity();
        if ($this->request->is('post')) {
            try {
                if ($this->request->getData('group_name')) {
                    $findDuplicate = $this->WatchlistForex->find()
                        ->where(['name' => $this->request->getData('group_name'), 'user_id' => $this->jwtPayload->id])->first();
                    if (!$findDuplicate) {
                        $watchlistForex = $this->WatchlistForex->patchEntity($watchlistForex, $this->request->getData());
                        $watchlistForex->user_id = $this->jwtPayload->id;
                        $watchlistForex->name = $this->request->getData('group_name');
                        $watchlistForex->is_default = 0;
                        $response = $this->WatchlistForex->add($watchlistForex);

                        if ($response == 1) {
                            $this->httpStatusCode = 404;
                            $this->apiResponse['error'] = 'You need to upgrade your plan to create new watchlists';
                        } elseif ($response == 2) {
                            $this->httpStatusCode = 404;
                            $this->apiResponse['error'] = 'You already created 4 forex watchlist. Upgrade your plan to create more.';
                        } else {
                            $this->apiResponse['message'] = $response['message'];
                            $this->apiResponse['data'] = $response['watchlist'];
                        }
                    } else {
                        $this->apiResponse['error'] = 'This forex group already exists';
                    }
                } else {
                    $this->apiResponse['error'] = 'enter name';
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        }
    }

    /**
     * edit method it will edit the watchlist group
     *
     * @param int $id Watchlist id
     * @return void
     */
    public function edit($id)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            try {
                if ($this->request->getData('group_name')) {
                    $watchlistForex = $this->WatchlistForex->get($id);
                    $watchlistForex = $this->WatchlistForex->patchEntity($watchlistForex, $this->request->getData());
                    $watchlistForex->name = $this->request->getData('group_name');
                    if ($this->WatchlistForex->save($watchlistForex)) {
                        $this->apiResponse['message'] = 'Watchlist group successfully edited';
                        $this->apiResponse['data'] = $watchlistForex;
                    } else {
                        $this->apiResponse['error'] = 'error';
                    }
                } else {
                    $this->apiResponse['error'] = 'enter name';
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        }
    }

    /**
     * addItem method it will add or remove the item to the watchlist.
     *
     * @param int $traderId Trader id
     * @return void
     */
    public function addItem($traderId)
    {
        $watchlistItem = $this->WatchlistForex->WatchlistForexItems->newEntity();
        if ($this->request->is('post')) {
            try {
                if ($this->request->getData('watchlist_forex_group_id')) {
                    $findDuplicate = $this->WatchlistForexItems->find()
                        ->where(['trader_id' => $traderId, 'watchlist_forex_id' => $this->request->getData('watchlist_forex_group_id'), 'user_id' => $this->jwtPayload->id])->first();
                    if (!$findDuplicate) {
                        $watchlistItem = $this->WatchlistForex->WatchlistForexItems->patchEntity($watchlistItem, $this->request->getData());
                        $watchlistItem->user_id = $this->jwtPayload->id;
                        $watchlistItem->trader_id = $traderId;
                        $watchlistItem->watchlist_forex_id = $this->request->getData('watchlist_forex_group_id');
                        if ($this->WatchlistForex->WatchlistForexItems->save($watchlistItem)) {
                            $watchlistForex = $this->WatchlistForex->find('list')
                                ->where(['user_id' => $this->jwtPayload->id]);
                            $data['watchlistItem'] = $watchlistItem;
                            //$data['watchlistForex'] = $watchlistForex;
                            $this->apiResponse['message'] = 'Watchlist forex item successfully added';
                            $this->apiResponse['data'] = $data;
                        } else {
                            $this->apiResponse['error'] = 'error';
                        }
                    } else {
                        $this->apiResponse['error'] = 'already added to watchlist';
                    }
                } else {
                    $this->apiResponse['error'] = 'enter watchlist forex group id';
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        }
    }

    /**
     * removeItem method it will add or remove the item to the watchlist.
     *
     * @param int $traderId Trader id
     * @return void
     */
    public function removeItem($traderId)
    {
        $this->request->allowMethod(['post', 'delete']);
        try {
            $watchlistItem = $this->WatchlistForex->WatchlistForexItems->find()
                ->where([
                    'user_id' => $this->jwtPayload->id,
                    'trader_id' => $traderId
                ])->first();
            if ($watchlistItem) {
                if ($this->WatchlistForex->WatchlistForexItems->delete($watchlistItem)) {
                    $this->apiResponse['message'] = 'Watchlist forex item successfully deleted';
                } else {
                    $this->apiResponse['error'] = 'error';
                }
            } else {
                $this->apiResponse['error'] = 'No matched data found ';
            }
        } catch (\Exception $e) {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = $e->getMessage();
        }
    }

    /**
     * delete method it will add or remove group.
     *
     * @param int $id Watchlist Forex id
     * @return void
     */
    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $watchlistItem = $this->WatchlistForex->get($id);
        try {
            if ($this->WatchlistForex->delete($watchlistItem)) {
                $this->WatchlistForexItems->deleteAll(['watchlist_forex_id' => $id]);
                $this->apiResponse['message'] = 'Forex watchlist removed succesfully.';
            } else {
                $this->apiResponse['error'] = 'error';
            }
        } catch (\Exception $e) {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = $e->getMessage();
        }
    }

    public function getAllForexGroup()
    {
        $this->request->allowMethod('get');
        if ($this->jwtPayload->id) {
            try {
                $all_watchlists = $this->WatchlistForex->find('all')
                    ->where(['user_id' => $this->jwtPayload->id])
                    ->order('created DESC')
                    ->toArray();
                if ($all_watchlists) {
                    $this->apiResponse['data'] = $all_watchlists;
                    $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['WatchlistGroup'];
                } else {
                    $this->httpStatusCode = 404;
                    $this->apiResponse['error'] = 'No data has found.';
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = $e->getMessage();;
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'please login to continue';
        }
    }

    /* get all my group wise forex watchlist
     * */
    public function myForexGroup()
    {
        $this->request->allowMethod('get');
        $this->loadModel('Api.WatchlistForex');
        $this->paginate = [
            'fields' => ['id' => 'WatchlistForex.id', 'user_id' => 'WatchlistForex.user_id',
                'group_name' => 'WatchlistForex.name', 'is_default' => 'WatchlistForex.is_default'],
            'contain' => ['WatchlistForexItems'],
        ];
        $watchlist = $this->paginate($this->WatchlistForex->find()
            ->where(['WatchlistForex.user_id' => $this->jwtPayload->id]))->toArray();
        $result = [];
        foreach ($watchlist as $watch) {
            $response = $this->myForexGroupData($watch['id']);
            if ($response) {
                $result[] = ['id' => $watch['id'], 'group_name' => $watch['group_name'], 'is_default' => $watch['is_default'], 'watchlist' => $response];
            } else {
                $result[] = ['id' => $watch['id'], 'group_name' => $watch['group_name'], 'is_default' => $watch['is_default'], 'watchlist' => null];
            }
        }
        if ($result) {
            $this->apiResponse['data'] = $result;
        } else {
            $this->apiResponse['data'] = [];
        }

    }

    /* this function is used to myForexGroup() in this controller to findout group forex watchlist
     * */
    private function myForexGroupData($group_id)
    {
        $this->loadModel('Companies');
        $this->add_model(array('Api.WatchlistForexItems', 'Api.Trader'));

        $watchlists = $this->WatchlistForexItems->find()->select(['id' => 'WatchlistForexItems.id',
            'trader_id' => 'Trader.id', 'from_currency_code' => 'Trader.from_currency_code',
            'from_currency_name' => 'Trader.from_currency_name', 'to_currency_code' => 'Trader.to_currency_code',
            'to_currency_name' => 'Trader.to_currency_name', 'exchange_rate' => 'Trader.exchange_rate'])
            ->contain('Trader')
            ->where(['WatchlistForexItems.user_id' => $this->jwtPayload->id, 'watchlist_forex_id' => $group_id])
            ->toArray();
        $watchlist = [];
        if (!empty($watchlists)) {
            foreach ($watchlists as $item) {
                $watchlist[] = ['trader_id' => $item['trader_id'], 'from_currency_code' => $item['from_currency_code'],
                    'to_currency_code' => $item['to_currency_code'], 'from_currency_name' => $item['from_currency_name'],
                    'to_currency_name' => $item['to_currency_name'], 'exchange_rate' => $item['exchange_rate']];
            }
        }
        return $watchlist;
    }
}
