<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class IpoController extends AppController
{

    /**
     * get Ipo markets with companies
     *
     * @param $market string
     * @param $company string
     * @return json return json response if there is no logged in user
     */
    public function index($market = null, $company = null)
    {
        if (!$this->Auth->user()) {
            $this->redirect(['_name' => 'login']);
        }
        $allMarkets = $this->getAllMarkets();
        if (!is_null($allMarkets) && count($allMarkets)) {
            $currentMarket = $this->getCurrentMarket($allMarkets, $market);
            $data['allMarkets'] = $allMarkets;

            $data['currentMarket'] = $currentMarket;

            $data['currentCompany'] = $this->getCurrentCompany($currentMarket->ipo_company, $company);

            $data['isUserInterested'] = $this->searchForUser($this->Auth->user()['id'], $data['currentCompany']['ipo_interested_users']);
        } else {
            $data = false;
        }
        $this->set(compact('data'));
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

            $this->loadModel('IpoInterestedUsers');

            $data['app_user_id'] = $this->Auth->user('id');
            $data['ipo_company_id'] = $companyId;

            $result = $this->IpoInterestedUsers->setIpoInterestedUser($data);

            if ($result) {
                $this->Flash->success(__('You are interested successfully.'));
            } else {
                $this->Flash->error(__('Some error accrued.'));
            }

            $this->redirect($this->referer());
        }
    }

    /**
     * notInterest method this method will unset an interesting company for user.
     *
     * @param integer $interestId.
     * @return void
     */
    public function notInterest($interestId)
    {
        if (!is_null($interestId)) {

            $this->loadModel('IpoInterestedUsers');

            $result = $this->IpoInterestedUsers->deleteIpoInterestedUser($interestId);
            if ($result) {
                $this->Flash->success(__('You are not interested successfully.'));
            } else {
                $this->Flash->error(__('Some error occrued.'));
            }

            $this->redirect($this->referer());
        }
    }

    /**
     * setJsonResponse method this method will set make response.
     *
     * @param object $response.
     * @return void
     */
    protected function setJsonResponse($response)
    {
        $response = json_encode($response);
        $this->response->type('application/json');
        $this->response->body($response);
    }

    /**
     * getCurrentMarket method.
     *
     * @param array $allMarkets, entity $market.
     * @return entity
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
        $this->loadModel('IpoMarket');
        $ipoMarkets = $this->IpoMarket->find()
                ->where(['IpoMarket.status'=>'enabled'])
                ->contain(['IpoCompany' => function ($q)
                    {
                        return $q->autoFields(false)
                                ->where(['IpoCompany.status'=>'enabled'])
                                ->contain(['IpoInterestedUsers' => function ($q)
                                    {
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
                    return $user->id;
                }
            }
        }
        return false;
    }

}
