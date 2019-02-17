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
class ResearchCompanyController extends AppController
{

    public function index()
    {
        $this->set('_serialize');
    }

    /**
     * all method this method will list all Research companies.
     *
     * @return void
     */
    public function all()
    {
        $this->loadModel('ResearchCompany');

        $researchCompanies = $this->paginate($this->ResearchCompany->find('all')->contain('ResearchMarket'));

        $this->set(compact('researchCompanies'));
    }

    /**
     * add method this method will add an Research company.
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $this->loadModel('ResearchCompany');

            $result = $this->ResearchCompany->addResearchCompany($this->request->data('contact'));

            if ($result) {
                $this->Flash->success(__('Research company successfully added.'));
            } else {
                $this->Flash->error(__('Research company was not added.'));
            }

            $this->redirect(['_name'=>'all_research_companies']);
        }

        $this->loadModel('ResearchMarket');

        $all_research_markets = $this->ResearchMarket
            ->find('all', [
                'fields' => ['id', 'name']
            ])
            ->toArray();

        $this->set(compact('all_research_markets'));

    }

    /**
     * edit method this method will edit an Research company.
     *
     * @param integer $id
     * @return void
     */
    public function edit($id)
    {
        if (empty($id)) {
            $this->redirect(['_name' => 'all_research_companies']);
        }
        $this->loadModel('ResearchCompany');
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $result = $this->ResearchCompany->editResearchCompany($this->request->data('contact'), $id);

            if ($result) {
                $this->Flash->success(__('Research company successfully edited.'));
            } else {
                $this->Flash->error(__('Research company was not edited.'));
            }

            $this->redirect(['_name'=>'all_research_companies']);
        }

        $researchCompany = $this->ResearchCompany->get($id);
        $this->set(compact('researchCompany'));

        $this->loadModel('ResearchMarket');

        $all_research_markets = $this->ResearchMarket
            ->find('all', [
                'fields' => ['id', 'name']
            ])
            ->toArray();

        $this->set(compact('all_research_markets'));

    }

    /**
     * delete method this method will delete an Research company.
     *
     * @param integer $id
     * @return void
     */
    public function delete($id)
    {
        $this->loadModel('ResearchCompany');
        $result = $this->ResearchCompany->deleteResearchCompany($id);

        if ($result) {
            $this->Flash->success(__('Research company successfully deleted.'));
        } else {
            $this->Flash->error(__('Research company was not deleted.'));
        }

        $this->redirect(['_name'=>'all_research_companies']);
    }

    /**
     * disable method this method will disable an Research company.
     *
     * @param integer $id
     * @return void
     */
    public function disable($id)
    {

        $this->loadModel('ResearchCompany');
        $result = $this->ResearchCompany->disableResearchCompany($id);

        if ($result) {
            $this->Flash->success(__('Research company is disabled.'));
        } else {
            $this->Flash->error(__('Research company was not disabled.'));
        }

        $this->redirect(['_name'=>'all_research_companies']);
    }
     /**
     * disable method this method will disable an Research company.
     *
     * @param integer $id
     * @return void
     */
    public function enable($id)
    {

        $this->loadModel('ResearchCompany');
        $result = $this->ResearchCompany->enableResearchCompany($id);

        if ($result) {
            $this->Flash->success(__('Research company is enable.'));
        } else {
            $this->Flash->error(__('Research company was not enable.'));
        }

        $this->redirect(['_name'=>'all_research_companies']);
    }
}
