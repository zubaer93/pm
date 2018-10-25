<?php

namespace Api\Controller;


use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;


class IpoController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    /**
     * get Ipo markets with companies
     *
     * @param $market string
     * @param $company string
     * @return json return json response if there is no logged in user
     */
    public function index($market, $company)
    {
        $this->add_model(array('Api.Users'));
        $user = $this->jwtPayload;
        if ($user) {
            $allMarkets = $this->getAllMarkets();
            if (!is_null($allMarkets) && count($allMarkets)) {
                $currentMarket = $this->getCurrentMarket($allMarkets, $market);
                $data['currentCompany'] = $this->getCurrentCompany($currentMarket->ipo_company, $company);
                $data['interest'] = $this->searchForUser($user->id, $data['currentCompany']['ipo_interested_users']);
                $data['isUserInterested'] = !!$data['interest'];
            } else {
                $data = false;
            }
            if (!empty($data)) {
                $this->apiResponse['data'] = $data;
                //$this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Transactions'];
            } else {
                $this->apiResponse['data'] = [];
                //$this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['Transactions'];
            }
        } else {
            $this->httpStatusCode = 404;
            $this->apiResponse['error'] = 'You are not signed in';
        }
    }

    /**
     * interest method this method will set an interesting company for user.
     *
     * @param integer $companyId IPO Company ID.
     * @return void
     */
    public function interest($companyId)
    {
        if (!is_null($companyId)) {

            $this->loadModel('Api.IpoInterestedUsers');

            $data['app_user_id'] = $this->jwtPayload->id;
            $data['ipo_company_id'] = $companyId;

            $result = $this->IpoInterestedUsers->setIpoInterestedUser($data);

            if ($result) {
                $this->apiResponse = ['message' => 'Successfully added to the interested list'];
            } else {
                $this->apiResponse['error'] = 'Could not add to the interested list';
                $this->httpStatusCode = 503;
            }

        }
    }

    /**
     * notInterest method this method will unset an interesting company for user.
     *
     * @param $company_id
     * @return void
     */
    public function notInterest($company_id)
    {
        $this->loadModel('Api.IpoInterestedUsers');
        $interest = $this->IpoInterestedUsers->find()->where(['app_user_id' => $this->jwtPayload->id, 'ipo_company_id' => $company_id])->first();
        if (!is_null($interest)) {
            $result = $this->IpoInterestedUsers->delete($interest);
            if ($result) {
                $this->apiResponse['message'] = 'Interest removed successfully.';
            } else {
                $this->apiResponse['error'] = 'Something went wrong';
                $this->httpStatusCode = 400;
            }
        }
    }

    public function markets()
    {
        $markets = $this->getMarkets();
        $this->apiResponse['data'] = $markets;
    }

    /**
     * getCurrentMarket method.
     *
     * @param array $allMarkets , entity $market.
     * @param $market
     * @return array
     */
    private function getCurrentMarket($allMarkets, $market)
    {
        if (!is_null($market)) {
            return $this->slugSearch($market, $allMarkets);
        } else {
            return $allMarkets[0];
        }
    }

    /**
     * getAllMarkets method.
     *
     * @return array
     */
    private function getAllMarkets()
    {
        $this->loadModel('Api.IpoMarket');
        $ipoMarkets = $this->IpoMarket->find()
            ->where(['IpoMarket.status' => 'enabled'])
            ->contain(['IpoCompany' => function ($q) {
                return $q->autoFields(false)
                    ->where(['IpoCompany.status' => 'enabled'])
                    ->contain(['IpoInterestedUsers' => function ($q) {
                        return $q->autoFields(false);
                    }]);
            }])
            ->toArray();
        return $ipoMarkets;
    }

    private function getMarkets()
    {
        $this->loadModel('Api.IpoMarket');
        $ipoMarkets = $this->IpoMarket->find()
            ->where(['IpoMarket.status' => 'enabled'])
            ->contain(['IpoCompany' => function ($q) {
                return $q->autoFields(false)
                    ->select(['id', 'ipo_market_id', 'name', 'slug'])
                    ->where(['IpoCompany.status' => 'enabled'])
                    ->contain(['IpoInterestedUsers' => function ($q) {
                        return $q->autoFields(false);
                    }]);
            }])
            ->toArray();
        return $ipoMarkets;
    }

    /**
     * searchForUser method.
     *
     * @param stirng $userId
     * @param array $interestedUsers
     * @return bool|integer
     */
    private function searchForUser($userId, $interestedUsers)
    {
        if (!is_null($interestedUsers)) {
            foreach ($interestedUsers as $key => $user) {
                if ($user->app_user_id === $userId) {
                    return $user;
                }
            }
        }
        return false;
    }

    /**
     * getCurrentCompany method.
     *
     * @param array $currentMarket , entity $company.
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
     * @param array $slug , entity $allMarkets.
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
}