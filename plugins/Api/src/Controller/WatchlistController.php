<?php

namespace Api\Controller;


class WatchlistController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');

    }

    /* this function will return user wise watchlist
     * */
    public function getWatchlistAll()
    {
        $this->loadModel('Companies');
        $this->add_model(array('Api.Watchlist', 'Api.Companies', 'Api.WatchlistGroup','Api.Stocks'));
        $this->paginate = [
            'fields' => ['id' => 'Watchlist.id', 'user_id' => 'Watchlist.user_id', 'company_id' => 'Watchlist.company_id', 'company_name' => 'Companies.name', 'company_symbol' => 'Companies.symbol'
                , 'group_id' => 'Watchlist.group_id', 'group_name' => 'WatchlistGroup.name', 'created_at' => 'Watchlist.created'],
            'contain' => ['Companies', 'WatchlistGroup'],
        ];
        $watchlist = $this->paginate($this->Watchlist->find()
            ->where(['Watchlist.user_id' => $this->jwtPayload->id])
            ->order(['Watchlist.created' => 'desc ']))
            ->toArray();
        foreach ($watchlist as $item) {
            $item['stock_info'] = $this->Companies->getStocksInfo3([$item->company_symbol], $this->currentLanguage);
            if (!empty($item['stock_info']) && count($item['stock_info']) > 0) {
                $item['stock_info'] = $item['stock_info'][0];
            }
        }
        if (!empty($watchlist)) {
            $this->apiResponse['data'] = $watchlist;
        } else {
            $this->apiResponse['data'] = [];
        }
    }

    /*this function will create watchlist for a user
         *
         * */
    public function createWatchlist()
    {

        $this->request->allowMethod('post');
        $this->add_model(array('Api.Watchlist'));
        $data = $this->request->getData();
        try {
            if (isset($data) && !empty($data['company_id'])) {
                $data['user_id'] = $this->jwtPayload->id;
                if ($this->Watchlist->addWatchlistAndSimulations($data['user_id'], $data, $this->currentLanguage)) {
                    $this->apiResponse['message'] = 'watchlist added successfully.';
                } else {
                    $this->httpStatusCode = 404;
                    $this->apiResponse['error'] = 'watchlist cannot be added.';
                }
            } else {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'please insert data';
            }
        } catch (\Exception $e) {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = $e->getMessage();
        }

    }

    public function deleteWatchlist($id)
    {
        $this->loadModel('Api.Watchlist');
        if ($this->jwtPayload->id) {
            try {
                $watchlist = $this->Watchlist->find()->where(['id' => $id, 'user_id' => $this->jwtPayload->id])->first();
                if ($watchlist) {
                    if ($this->Watchlist->delete($watchlist)) {
                        $this->apiResponse['message'] = 'Watchlist has been deleted successfully.';
                    } else {
                        $this->httpStatusCode = 404;
                        $this->apiResponse['error'] = 'Watchlist could not be deleted. Please try again.';
                    }
                } else {
                    $this->httpStatusCode = 404;
                    $this->apiResponse['error'] = 'Watchlist id and user id not found in table';
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

    public function getAllGroup()
    {
        $this->request->allowMethod('get');
        $this->loadModel('Api.WatchlistGroup');
        if ($this->jwtPayload->id) {
            try {
                $all_watchlists = $this->WatchlistGroup->find('all')
                    ->where(['user_id' => $this->jwtPayload->id])
                    ->order('created_at DESC')
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

    public function getWatchlistGroup()
    {
        $this->request->allowMethod('get');
        $group_id = $this->request->getQuery('group_id');
        if ($group_id) {
            $this->loadModel('Api.Watchlist');
            $this->loadModel('Api.WatchlistGroup');
            $watchlist = $this->Watchlist->getWatchlistGroup($this->jwtPayload->id, $this->currentLanguage, $group_id);          
            if (!empty($watchlist)) {
                $this->apiResponse['data'] = $watchlist;
            } else {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'no watchlist group found';
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'Insert group id';
        }
    }


    public function createWatchlistGroup()
    {
        $this->request->allowMethod('post');
        $data = $this->request->getData();
        $this->loadModel('Api.WatchlistGroup');
        if (empty($data['group_name'])) {
            $this->apiResponse['error'] = 'Group name required';
            return;
        }
        try {
            if ($data['group_name'] != null) {
                $findDuplicate = $this->WatchlistGroup->find()
                    ->where(['name' => $data['group_name'], 'user_id' => $this->jwtPayload->id])->first();
                if($findDuplicate){
                    $this->apiResponse['error'] = 'Watchlist group already exists';
                    return;
                }
                $entity = $this->WatchlistGroup->newEntity();
                $entity->user_id = $this->jwtPayload->id;
                $entity->name = $data['group_name'];
                $entity->is_default = 0;
                $response = $this->WatchlistGroup->add($entity);

                if($response == 1){
                    $this->httpStatusCode = 404;
                    $this->apiResponse['error'] = 'You need to upgrade your plan to create new watchlists';
                }
                elseif($response == 2){
                    $this->httpStatusCode = 404;
                    $this->apiResponse['error'] = 'You already created 4 watchlist. Upgrade your plan to create more.';
                }
                else{
                    $this->apiResponse['message'] = $response['message'];
                    $this->apiResponse['data'] = $response['watchlist'];
            }
            } else {
                $this->httpStatusCode = 404;
                $this->apiResponse['error'] = 'Insert group name';
            }
        } catch (\Exception $e) {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = $e->getMessage();
        }
    }

    public function  deleteBySymbol($symbol){
        $this->request->allowMethod('delete');
        $this->loadModel('Api.Watchlist');
        $this->loadModel('Api.Companies');
        if(!empty($symbol)){
            $company = $this->Companies->find()->where(['symbol' => $symbol])->first();
            if($this->Watchlist->deleteAll(['company_id' => $company['id']])){
                $this->apiResponse['message'] = 'Watchlist removed successfully.';
            } else{
                $this->httpStatusCode = 400;
                $this->apiResponse['error'] = 'Watchlist could not be removed. Please try again.';
            }
        } else{
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please enter symbol.';
        }
    }
    /* get all my group wise watchlist
     * */
    public function myWatchlistGroup(){
        $this->request->allowMethod('get');
        $this->loadModel('Api.Companies');
        $this->loadModel('Api.WatchlistGroup');
        $user_id=$this->jwtPayload->id;
        $this->paginate = [
            'contain' => ['Watchlist'=>function($q) use($user_id) {
                return $q->select(['id' => 'MAX(Watchlist.id)',  'company_id' => 'MAX(Watchlist.company_id)',
                    'company_name' => 'MAX(Companies.name)', 'company_symbol' => 'MAX(Companies.symbol)'
                    , 'group_id' => 'Watchlist.group_id'])
                    ->contain('Companies')
                    ->distinct(['Companies.symbol'])
                    ->where(['Watchlist.user_id' => $user_id]);
            }],
        ];

        $watchlist = $this->paginate($this->WatchlistGroup->find()
            ->where(['WatchlistGroup.user_id' => $this->jwtPayload->id]))->toArray();
        if(!empty($watchlist)){
            foreach ($watchlist as $list) {
                foreach ($list['watchlist'] as $item){
                    $item['stock_info'] = $this->Companies->getStocksInfo3([$item->company_symbol], $this->currentLanguage);
                    if (!empty($item['stock_info']) && count($item['stock_info']) > 0) {
                        $item['stock_info'] = $item['stock_info'][0];
                    }
                }
            }
            $this->apiResponse['data'] = $watchlist;
        }else{
            $this->apiResponse['data'] = [];
        }

    }
    /* this function is used to myWatchlistGroup() in this controller to findout group wise watchlist
     * */
    private function myWatchlistGroupData($group_id){
        $this->loadModel('Companies');
        $this->add_model(array('Api.Watchlist', 'Api.Companies', 'Api.WatchlistGroup'));
        $this->paginate = [
            'fields' => ['id' => 'MAX(Watchlist.id)',  'company_id' => 'MAX(Watchlist.company_id)', 'company_name' => 'MAX(Companies.name)', 'company_symbol' => 'MAX(Companies.symbol)'
                , 'group_id' => 'Watchlist.group_id'],
            'contain' => ['Companies'],
            'distinct' => ['Companies.symbol']
        ];

        $watchlists = $this->Watchlist->find()->select(['id' => 'MAX(Watchlist.id)',  'company_id' => 'MAX(Watchlist.company_id)',
            'company_name' => 'MAX(Companies.name)', 'company_symbol' => 'MAX(Companies.symbol)'
            , 'group_id' => 'Watchlist.group_id'])
            ->contain('Companies')
            ->distinct(['Companies.symbol'])
            ->where(['Watchlist.user_id' => $this->jwtPayload->id, 'group_id' => $group_id])
            ->toArray();
        $watchlist = [];
        if(!empty($watchlists)){
            foreach ($watchlists as $item) {
                $item['stock_info'] = $this->Companies->getStocksInfo3([$item->company_symbol], $this->currentLanguage);
                if (!empty($item['stock_info']) && count($item['stock_info']) > 0) {
                    $item['stock_info'] = $item['stock_info'][0];
                    $watchlist[] = $item;
                }
            }
        }
        return $watchlist;
    }

    /**
     * edit method it will edit the watchlist group
     *
     * @param int $id Watchlist id
     * @return void
     */
    public function editGroup($id)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->loadModel('Api.WatchlistGroup');
            try{
                if($this->request->getData('group_name')){
                    $watchlistStock = $this->WatchlistGroup->find()->where(['id' => $id, 'user_id' => $this->jwtPayload->id])->first();
                    if($watchlistStock){
                        $watchlistStock = $this->WatchlistGroup->patchEntity($watchlistStock, $this->request->getData());
                        $watchlistStock->name = $this->request->getData('group_name');
                        if ($this->WatchlistGroup->save($watchlistStock)) {
                            $this->apiResponse['message'] = 'Watchlist group successfully edited';
                            $this->apiResponse['data'] = $watchlistStock;
                        } else {
                            $this->httpStatusCode = 404;
                            $this->apiResponse['error'] = 'error';
                        }
                    }else{
                        $this->httpStatusCode = 404;
                        $this->apiResponse['error'] = 'No such group found for this user';
                    }
                }else{
                    $this->httpStatusCode = 404;
                    $this->apiResponse['error'] = 'enter group name';
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        }
    }
      /**
     * delete method it will remove group.
     *
     * @param int $id Watchlist group id
     * @return void
     */
    public function deleteGroup($id)
    {
        $this->request->allowMethod(['delete']);
        $this->loadModel('Api.WatchlistGroup');
        $this->loadModel('Api.Watchlist');
        $watchlistStock = $this->WatchlistGroup->find()->where(['id' => $id, 'user_id' => $this->jwtPayload->id])->first();
        
        if($watchlistStock){
            try{
                if ($this->WatchlistGroup->delete($watchlistStock)) {
                    $this->Watchlist->deleteAll(['group_id' => $id]);
                    $this->apiResponse['message'] = 'Watchlist group removed succesfully.';
                } else {
                    $this->httpStatusCode = 403;
                    $this->apiResponse['error'] = 'error';
                }
            } catch (\Exception $e) {
                $this->httpStatusCode = 403;
                $this->apiResponse['error'] = $e->getMessage();
            }
        }else{
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'No such group found for this user';
        }
    }
}
