<?php

namespace Api\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;

class ForexController extends AppController
{
    public $paginate = [
        'limit' => 10
    ];
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }
    /**
     * This method will return all list of forex/traders
     */
    public function index()
    {
        $this->loadModel('Api.Trader');
        $data = $this->paginate($this->Trader->find('all'));

        $trader = $this->Trader->getTrader($data);
        $paginate = $this->Paginator->configShallow(['data' => $trader])->request->getParam('paging')['Trader'];
        if(isset($this->jwtPayload->id)){
            $trader = $this->watchlistChecker($trader, $this->jwtPayload->id);
        }
    
        if(!empty($trader)){
            $this->apiResponse['data'] = $trader;
            $this->apiResponse['paginate'] = $paginate;
        } else {
            $this->apiResponse['data'] = [];
        }
    }

    private function watchlistChecker($trader, $id){
        $this->add_model(array('Api.WatchlistForexItems'));
        $i=0;
        foreach($trader as $t){
            $watchlistForex = $this->WatchlistForexItems->find()
            ->where(['WatchlistForexItems.user_id' => $id,
            'WatchlistForexItems.trader_id' => $t['id']])
            ->first();

            if($watchlistForex){
                $trader[$i]['watchlisted'] = true;
            }
            else{
                $trader[$i]['watchlisted'] = false;
            }
            $i++;
        }
        return $trader;
    }

    public function symbol($currency)
    {
        $this->loadModel('Api.Trader');
        if(!empty($currency)){
            $exchangeInfo = $this->Trader->__getTraderInfoFromCurrency($currency);
            if(!empty($exchangeInfo)){
                $this->apiResponse['data'] = $exchangeInfo;
            } else {
                throw new NotFoundException();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please provide currency.';
        }
    }

}
