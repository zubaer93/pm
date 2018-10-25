<?php

namespace Api\Controller;

use Api\Controller\AppController;

class WatchlistBondsController extends AppController
{
    public function initialize()
    {
        $this->loadComponent('Paginator');
        parent::initialize();
        $this->add_model(array('Api.WatchlistBonds'));
        $this->add_model(array('Api.WatchlistBondItems'));
        $this->add_model(array('Api.Bonds'));
    }

    /**
     * add method it will add a new watchlist group.
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'fields' => ['id' => 'WatchlistBondItems.id', 'user_id' => 'WatchlistBondItems.user_id',
                'isin_code' => 'WatchlistBondItems.isin_code', 'watchlist_bond_id' => 'WatchlistBondItems.watchlist_bond_id',
                'watchlist_bond_name' => 'WatchlistBonds.name', 'created_at' => 'WatchlistBondItems.created'],
            'contain' => ['WatchlistBonds'],
        ];
        $watchlistBonds = $this->paginate($this->WatchlistBondItems->find()
            ->where(['WatchlistBondItems.user_id' => $this->jwtPayload->id])
            ->order(['WatchlistBondItems.created' => 'desc ']))
            ->toArray();
        foreach ($watchlistBonds as $item) {
            $item['bond_info'] = $this->Bonds->getBondInfo($item->isin_code, $this->jwtPayload->id, $this->currentLanguage);
            // if (!empty($item['bond_info']) && count($item['bond_info']) > 0) {
            //     $item['bond_info'] = $item['bond_info'][0];
            // }
        }
        if (!empty($watchlistBonds)) {
            $this->apiResponse['data'] = $watchlistBonds;
        } else {
            $this->apiResponse['data'] = [];
        }
    }

    public function add()
    {
        $watchlistBond = $this->WatchlistBonds->newEntity();
        if ($this->request->is('post')) {
            try {
                if ($this->request->getData('group_name')) {
                    $findDuplicate = $this->WatchlistBonds->find()
                        ->where(['name' => $this->request->getData('group_name'), 'user_id' => $this->jwtPayload->id])->first();
                    if (!$findDuplicate) {
                        $watchlistBond = $this->WatchlistBonds->patchEntity($watchlistBond, $this->request->getData());
                        $watchlistBond->user_id = $this->jwtPayload->id;
                        $watchlistBond->name = $this->request->getData('group_name');
                        $watchlistBond->is_default = 0;
                        $response = $this->WatchlistBonds->add($watchlistBond);

                        if ($response == 1) {
                            $this->httpStatusCode = 404;
                            $this->apiResponse['error'] = 'You need to upgrade your plan to create new watchlists';
                        } elseif ($response == 2) {
                            $this->httpStatusCode = 404;
                            $this->apiResponse['error'] = 'You already created 4 bond watchlist. Upgrade your plan to create more.';
                        } else {
                            $this->apiResponse['message'] = $response['message'];
                            $this->apiResponse['data'] = $response['watchlist'];
                        }
                    } else {
                        $this->apiResponse['error'] = 'This bond group already exists';
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
     * edit method it will edit the current watchlist
     *
     * @param int $id Watchlist id
     * @return void
     */
    public function edit($id)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            try {
                if ($this->request->getData('group_name')) {
                    $watchlistBond = $this->WatchlistBonds->get($id);
                    $watchlistBond = $this->WatchlistBonds->patchEntity($watchlistBond, $this->request->getData());
                    $watchlistBond->name = $this->request->getData('group_name');
                    if ($this->WatchlistBonds->save($watchlistBond)) {
                        $this->apiResponse['message'] = 'Watchlist group successfully edited';
                        $this->apiResponse['data'] = $watchlistBond;
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
     * @param string $isinCode Bond code
     * @return void
     */
    public function addItem($isinCode)
    {
        $watchlistItem = $this->WatchlistBonds->WatchlistBondItems->newEntity();
        if ($this->request->is('post')) {
            try {
                if ($this->request->getData('watchlist_bond_group_id')) {
                    $findDuplicate = $this->WatchlistBondItems->find()
                        ->where(['isin_code' => $isinCode, 'watchlist_bond_id' => $this->request->getData('watchlist_bond_group_id'), 'user_id' => $this->jwtPayload->id])->first();
                    if (!$findDuplicate) {
                        $watchlistItem = $this->WatchlistBonds->WatchlistBondItems->patchEntity($watchlistItem, $this->request->getData());
                        $watchlistItem->user_id = $this->jwtPayload->id;
                        $watchlistItem->isin_code = $isinCode;
                        $watchlistItem->watchlist_bond_id = $this->request->getData('watchlist_bond_group_id');
                        if ($this->WatchlistBonds->WatchlistBondItems->save($watchlistItem)) {
                            $watchlistBonds = $this->WatchlistBonds->find('list')
                                ->where(['user_id' => $this->jwtPayload->id]);
                            $data['watchlistItem'] = $watchlistItem;
                            //$data['watchlistBonds'] = $watchlistBonds;
                            $this->apiResponse['message'] = 'Watchlist bond item successfully added';
                            $this->apiResponse['data'] = $data;
                        } else {
                            $this->apiResponse['error'] = 'error';
                        }
                    } else {
                        $this->apiResponse['error'] = 'already added to watchlist';
                    }
                } else {
                    $this->apiResponse['error'] = 'enter watchlist bond group id';
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
    public function removeItem($isinCode)
    {
        $this->request->allowMethod(['post', 'delete']);
        try {
            $watchlistItem = $this->WatchlistBonds->WatchlistBondItems->find()
                ->where([
                    'user_id' => $this->jwtPayload->id,
                    'isin_code' => $isinCode
                ])->first();
            if ($watchlistItem) {
                if ($this->WatchlistBonds->WatchlistBondItems->delete($watchlistItem)) {
                    $this->apiResponse['message'] = 'Watchlist bond item successfully deleted';
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
     * delete method it will add or remove the item to the watchlist.
     *
     * @param int $id Watchlist Forex id
     * @return void
     */
    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $watchlistItem = $this->WatchlistBonds->get($id);
        try {
            if ($this->WatchlistBonds->delete($watchlistItem)) {
                $this->WatchlistBondItems->deleteAll(['watchlist_bond_id' => $id]);
                $this->apiResponse['message'] = 'Bond watchlist removed succesfully.';
            } else {
                $this->apiResponse['error'] = 'error';
            }
        } catch (\Exception $e) {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = $e->getMessage();
        }
    }

    public function getAllBondsGroup()
    {
        $this->request->allowMethod('get');
        if ($this->jwtPayload->id) {
            try {
                $all_watchlists = $this->WatchlistBonds->find('all')
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

    /* get all my group wise bonds watchlist
     * */
    public function myBondGroup()
    {
        $this->request->allowMethod('get');
        $this->loadModel('Api.WatchlistBonds');
        $this->paginate = [
            'fields' => ['id' => 'WatchlistBonds.id', 'user_id' => 'WatchlistBonds.user_id',
                'group_name' => 'WatchlistBonds.name', 'is_default' => 'WatchlistBonds.is_default'],
            'contain' => ['WatchlistBondItems'],
        ];
        $result = [];
        $watchlist = $this->paginate($this->WatchlistBonds->find()
            ->where(['WatchlistBonds.user_id' => $this->jwtPayload->id]))->toArray();
        foreach ($watchlist as $watch) {
            $response = $this->myBondGroupData($watch['id']);
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

    /* this function is used to myBondGroup() in this controller to findout group bonds watchlist
     * */
    private function myBondGroupData($group_id)
    {
        $this->loadModel('Api.Bonds');
        $this->add_model(array('Api.WatchlistBondItems'));

        $watchlists = $this->WatchlistBondItems->find()->select(['id' => 'WatchlistBondItems.id',
            'isin_code' => 'WatchlistBondItems.isin_code'])
            ->where(['WatchlistBondItems.user_id' => $this->jwtPayload->id, 'watchlist_bond_id' => $group_id])
            ->toArray();
        $bond = [];
        if (!empty($watchlists)) {
            foreach ($watchlists as $item) {
                $bond_info = $this->Bonds->getBondInfo($item['isin_code'], $this->jwtPayload->id, $this->currentLanguage);
                $bond[] = $bond_info;
            }
        }
        return $bond;
    }
}
