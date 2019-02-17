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

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ResearchMarketController extends AppController
{

    public function index()
    {
        $this->set('_serialize');
    }

    /**
     * all method this method will list all Research markets.
     *
     * @return void
     */
    public function all()
    {
        $this->loadModel('ResearchMarket');

        $researchMarkets = $this->paginate($this->ResearchMarket);

        $this->set(compact('researchMarkets'));
    }

    /**
     * add method this method will add an Research market.
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $this->loadModel('ResearchMarket');

            $result = $this->ResearchMarket->addResearchMarket($this->request->data('contact'));

            if ($result) {
                $this->Flash->success(__('Research market successfully added.'));
            } else {
                $this->Flash->error(__('Research market was not added.'));
            }

            $this->redirect(['_name'=>'all_research_markets']);
        }

    }

    /**
     * edit method this method will edit an Research market.
     *
     * @param integer $id
     * @return void
     */
    public function edit($id)
    {
        if (empty($id)) {
            $this->redirect(['_name' => 'all_research_markets']);
        }
        $this->loadModel('ResearchMarket');
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $result = $this->ResearchMarket->editResearchMarket($this->request->data('contact'), $id);

            if ($result) {
                $this->Flash->success(__('Research market successfully edited.'));
            } else {
                $this->Flash->error(__('Research market was not edited.'));
            }

            $this->redirect(['_name'=>'all_research_markets']);
        }

        $researchMarket = $this->ResearchMarket->get($id);
        $this->set(compact('researchMarket'));

    }

    /**
     * delete method this method will delete an Research market.
     *
     * @param integer $id
     * @return void
     */
    public function delete($id)
    {
        $this->loadModel('ResearchMarket');
        $result = $this->ResearchMarket->deleteResearchMarket($id);

        if ($result) {
            $this->Flash->success(__('Research market successfully deleted.'));
        } else {
            $this->Flash->error(__('Research market was not deleted.'));
        }

        $this->redirect(['_name'=>'all_research_markets']);
    }

    /**
     * disable method this method will disable an Research market.
     *
     * @param integer $id
     * @return void
     */
    public function disable($id)
    {

        $this->loadModel('ResearchMarket');
        $result = $this->ResearchMarket->disableResearchMarket($id);

        if ($result) {
            $this->Flash->success(__('Research market is disabled.'));
        } else {
            $this->Flash->error(__('Research market was not disabled.'));
        }

        $this->redirect(['_name'=>'all_research_markets']);
    }
    /**
     * enable method this method will disable an Research market.
     *
     * @param integer $id
     * @return void
     */
    public function enable($id)
    {

        $this->loadModel('ResearchMarket');
        $result = $this->ResearchMarket->enableResearchMarket($id);

        if ($result) {
            $this->Flash->success(__('Research market is enabled.'));
        } else {
            $this->Flash->error(__('Research market was not enabled.'));
        }

        $this->redirect(['_name'=>'all_research_markets']);
    }

}
