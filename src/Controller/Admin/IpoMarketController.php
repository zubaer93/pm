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
class IpoMarketController extends AppController
{

    public function index()
    {
        $this->set('_serialize');
    }

    /**
     * all method this method will list all IPO markets.
     *
     * @return void
     */
    public function all()
    {
        $this->loadModel('IpoMarket');

        $ipoMarkets = $this->paginate($this->IpoMarket);

        $this->set(compact('ipoMarkets'));
    }

    /**
     * add method this method will add an IPO market.
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $this->loadModel('IpoMarket');

            $result = $this->IpoMarket->addIpoMarket($this->request->data('contact'));

            if ($result) {
                $this->Flash->success(__('Ipo market successfully added.'));
            } else {
                $this->Flash->error(__('Ipo market was not added.'));
            }

            $this->redirect(['_name'=>'all_ipo_markets']);
        }

    }

    /**
     * edit method this method will edit an IPO market.
     *
     * @param integer $id
     * @return void
     */
    public function edit($id)
    {
        if (empty($id)) {
            $this->redirect(['_name' => 'all_ipo_markets']);
        }
        $this->loadModel('IpoMarket');
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $result = $this->IpoMarket->editIpoMarket($this->request->data('contact'), $id);

            if ($result) {
                $this->Flash->success(__('Ipo market successfully edited.'));
            } else {
                $this->Flash->error(__('Ipo market was not edited.'));
            }

            $this->redirect(['_name'=>'all_ipo_markets']);
        }

        $ipoMarket = $this->IpoMarket->get($id);
        $this->set(compact('ipoMarket'));

    }

    /**
     * delete method this method will delete an IPO market.
     *
     * @param integer $id
     * @return void
     */
    public function delete($id)
    {
        $this->loadModel('IpoMarket');
        $result = $this->IpoMarket->deleteIpoMarket($id);

        if ($result) {
            $this->Flash->success(__('Ipo market successfully deleted.'));
        } else {
            $this->Flash->error(__('Ipo market was not deleted.'));
        }

        $this->redirect(['_name'=>'all_ipo_markets']);
    }

    /**
     * disable method this method will disable an IPO market.
     *
     * @param integer $id
     * @return void
     */
    public function disable($id)
    {

        $this->loadModel('IpoMarket');
        $result = $this->IpoMarket->disableIpoMarket($id);

        if ($result) {
            $this->Flash->success(__('Ipo market is disabled.'));
        } else {
            $this->Flash->error(__('Ipo market was not disabled.'));
        }

        $this->redirect(['_name'=>'all_ipo_markets']);
    }
    /**
     * enable method this method will disable an IPO market.
     *
     * @param integer $id
     * @return void
     */
    public function enable($id)
    {

        $this->loadModel('IpoMarket');
        $result = $this->IpoMarket->enableIpoMarket($id);

        if ($result) {
            $this->Flash->success(__('Ipo market is enabled.'));
        } else {
            $this->Flash->error(__('Ipo market was not enabled.'));
        }

        $this->redirect(['_name'=>'all_ipo_markets']);
    }

}
