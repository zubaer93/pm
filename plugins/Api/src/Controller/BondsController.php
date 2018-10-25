<?php

namespace Api\Controller;

use Cake\Network\Exception\NotFoundException;

class BondsController extends AppController
{
    /**
     * this method will fetch all bons
     */
    public function index()
    {
        $this->loadModel('Api.Bonds');
        $bonds = $this->Bonds->getBondsBondeValue();
        
        if(isset($this->jwtPayload->id)){
            $bonds = $this->watchlistChecker($bonds, $this->jwtPayload->id);
        }

        if (!empty($bonds)) {
            $this->apiResponse['data'] = $bonds;
        } else {
            $this->apiResponse['data'] = [];
        }

    }
    private function watchlistChecker($bonds, $id){
        $this->add_model(array('Api.WatchlistBondItems'));
        $i=0;
        foreach($bonds as $t){
            $watchlistBond = $this->WatchlistBondItems->find()
            ->where(['WatchlistBondItems.user_id' => $id,
            'WatchlistBondItems.isin_code' => $t['ISINCode']])
            ->first();

            if($watchlistBond){
                $bonds[$i]['watchlisted'] = true;
            }
            else{
                $bonds[$i]['watchlisted'] = false;
            }
            $i++;
        }
        return $bonds;
    }

    public function getCorporateBonds(){
        $this->add_model(array('Api.Bonds','Api.WatchlistBondItems'));
        $bonds = $this->Bonds->getCorporateBonds($this->currentLanguage);
        $cbonds = array();
        foreach($bonds as $b){
            $nestedData = [];
            $nestedData['country'] = null;
            $nestedData['callable'] = null;
            $nestedData['issuerNameInBoldLetters'] = $b['name'];
            $nestedData['ISINCode'] = $b['symbol'];
            $nestedData['bondPrice'] = $b['stocks']['info']['volume'];
            $nestedData['YieldChangeInBpUnit'] = null;
            $nestedData['rating'] = null;
            $nestedData['currentDate'] = date("Y-M-d H:i:s");
            $nestedData['bondMaturityDate'] = null;
            $nestedData['perpetual'] = null;
            $nestedData['countryOfRisk'] = null;
            $nestedData['currencyName'] = null;
            $nestedData['currency'] = $b['exchange']['country']['market'];
            $nestedData['priceChangeInPercentage'] = null;
            $nestedData['previousDate'] = null;
            $nestedData['priceChange'] = null;
            $nestedData['bondCoupon'] = null;
            $nestedData['yieldChangeInPercentage'] = null;
            $nestedData['maturityYrsRemain'] = null;
            $nestedData['bondAmountOut'] = null;
            $nestedData['bondYield'] = null;
            $nestedData['issuerNameInNormalLetters'] = null;
            $nestedData['countryName'] = null;
            $nestedData['yieldChange'] = null;
            $nestedData['countryOfRiskName'] = null;

            $cbonds[] = $nestedData;
        }
        if(isset($this->jwtPayload->id)){
            $cbonds = $this->watchlistChecker($cbonds, $this->jwtPayload->id);
        }
        if (!empty($cbonds)) {
            $this->apiResponse['data'] = $cbonds;
        } else {
            $this->apiResponse['data'] = [];
        }

        // $corporate = [];
        // foreach ($corporateBonds as $bond){
        //     $corporate['corporate_bond'][] = ['name' => $bond->name, 'isin_code' => $bond->symbol,
        //         'bond_yield' =>$bond->stocks['info']['volume'],
        //         'currency' => $bond->stocks['info']['close']];
        // }
    }

    /**
     * historicalPrice method It will show the historical prices.
     *
     * @param string $isinCode ISIN Code to find the historical
     * @return \Cake\Http\Response|void
     */
    public function historicalPrice($isinCode)
    {
        $this->request->allowMethod('get');
        if (!empty($isinCode)) {
            $this->loadModel('Bonds');
            $bonds = $this->Bonds->getHistorical($isinCode, $this->jwtPayload ? $this->jwtPayload->id : null, $this->currentLanguage);
            if (!empty($bonds)) {
                $this->apiResponse['data'] = $bonds;
            } else {
                throw new NotFoundException();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['data'] = 'Please provide isincode';
        }
    }

    /*get bond wise news
     * */
    public function news($isinCode)
    {
        if (!empty($isinCode)) {
            $this->loadModel('Bonds');
            $bonds = $this->Bonds->getHistorical($isinCode, $this->jwtPayload->id, $this->currentLanguage);
            if (!empty($bonds)) {
                $this->apiResponse['data'] = $bonds['news'];
            } else {
                throw new NotFoundException();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['data'] = 'Please provide isincode';
        }
    }

}
    