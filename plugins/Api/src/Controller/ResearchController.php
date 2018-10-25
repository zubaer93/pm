<?php

namespace Api\Controller;

class ResearchController extends AppController
{

    /**
     *this function will return the research content how to invest for jamaican market
     */
    public function howToInvest()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.ResearchCompany'));
        $invest = $this->ResearchCompany->find()->where(['slug' => 'how-to-invest'])->first();
        if(!empty($invest)){
            $this->apiResponse['data'] = $invest;
        } else {
            $this->apiResponse['data'] = (object)[];
        }
    }

    /**
     *this function will return the research content forex and crypto currencies for jamaican market
     */
    public function forexCrypto()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.ResearchCompany'));
        $forex = $this->ResearchCompany->find()->where(['slug' => 'forex-crypto'])->first();
        if(!empty($forex)){
            $this->apiResponse['data'] = $forex;
        } else {
            $this->apiResponse['data'] = (object)[];
        }
    }

    /**
     *this function will return the research content jamaica stock exchange for jamaican market
     */
    public function jamaicaStockExchange()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.ResearchCompany'));
        $stock = $this->ResearchCompany->find()->where(['slug' => 'jamaica-stock-exchange'])->first();
        if(!empty($stock)){
            $this->apiResponse['data'] = $stock;
        } else {
            $this->apiResponse['data'] = (object)[];
        }
    }

    /**
     *this function will return the research content strategies for jamaican market
     */
    public function strategies()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.ResearchCompany'));
        $strategies = $this->ResearchCompany->find()->where(['slug' => 'strategies'])->first();
        if(!empty($strategies)){
            $this->apiResponse['data'] = $strategies;
        } else {
            $this->apiResponse['data'] = (object)[];
        }
    }

    /**
     *this function will return the research content investing on us market for us market
     */
    public function investingUsMarket()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.ResearchCompany'));
        $us_investing = $this->ResearchCompany->find()->where(['slug' => 'investing-on-us-market'])->first();
        if(!empty($us_investing)){
            $this->apiResponse['data'] = $us_investing;
        } else {
            $this->apiResponse['data'] = (object)[];
        }
    }

    /**
     *this function will return the research content forex crypto currencies on us market for us market
     */
    public function forexUsMarket()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.ResearchCompany'));
        $us_investing = $this->ResearchCompany->find()->where(['slug' => 'forex-crypto-currencies'])->first();
        if(!empty($us_investing)){
            $this->apiResponse['data'] = $us_investing;
        } else {
            $this->apiResponse['data'] = (object)[];
        }
    }

    /**
     *this function will return the research content forex us stock exchange on us market
     */
    public function usStockExchange()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.ResearchCompany'));
        $us_investing = $this->ResearchCompany->find()->where(['slug' => 'the-us-stock-exchange'])->first();
        if(!empty($us_investing)){
            $this->apiResponse['data'] = $us_investing;
        } else {
            $this->apiResponse['data'] = (object)[];
        }
    }

    /**
     *this function will return the research content compare us broker on us market
     */
    public function compareUsBroker()
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.ResearchCompany'));
        $us_investing = $this->ResearchCompany->find()->where(['slug' => 'compare-us-broker'])->first();
        if(!empty($us_investing)){
            $this->apiResponse['data'] = $us_investing;
        } else {
            $this->apiResponse['data'] = (object)[];
        }
    }

    /**
     * getCurrentCompany method.
     *
     * @param array $currentMarket, entity $company.
     * @return entity
     */
    private function getCurrentCompany($currentMarket, $company)
    {
        if (!is_null($currentMarket)) {
            return $this->slugSearch($company, $currentMarket);
        } else {
            return $currentMarket;
        }
    }

    /**
     * slugSearch method.
     *
     * @param array $slug, entity $allMarkets.
     * @return entity
     */
    private function slugSearch($slug, $allMarkets)
    {
        if (count($allMarkets) > 0) {
            foreach ($allMarkets as $market) {
                if ($market->slug === $slug) {
                    return $market;
                }
            }

            return $allMarkets[0];
        }

        return false;
    }

    /**
     * getAllMarkets method.
     *
     * @return array
     */
    private function getAllMarkets()
    {
        $this->loadModel('ResearchMarket');
        $researchMarkets = $this->ResearchMarket->find()
                ->where(['ResearchMarket.status' => 'enabled'])
                ->contain(['ResearchCompany' => function ($q)
                    {
                        return $q->autoFields(false)
                                ->where(['ResearchCompany.status' => 'enabled']);
                    }])
                ->toArray();

        return $researchMarkets;
    }

}
