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
class ResearchController extends AppController
{

    /**
     * get Research markets with companies
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

        if (!is_null($allMarkets) && count($allMarkets) > 0) {
            $currentMarket = $this->getCurrentMarket($allMarkets, $market);
            $data['allMarkets'] = $allMarkets;

            $data['currentMarket'] = $currentMarket;

            $data['currentCompany'] = $this->getCurrentCompany($currentMarket->research_company, $company);
        } else {
            $data = false;
        }
        $this->set(compact('data'));
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
