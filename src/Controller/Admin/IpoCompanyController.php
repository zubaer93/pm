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
class IpoCompanyController extends AppController
{

    public function index()
    {
        $this->set('_serialize');
    }

    /**
     * all method this method will list all IPO companies.
     *
     * @return void
     */
    public function all()
    {
        $this->loadModel('IpoCompany');

        $ipoCompanies = $this->paginate($this->IpoCompany->find('all')->contain('IpoMarket'));

        $this->set(compact('ipoCompanies'));
    }

    /**
     * add method this method will add an IPO company.
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $this->loadModel('IpoCompany');

            $result = $this->IpoCompany->addIpoCompany($this->request->data('contact'));

            if ($result) {
                $this->Flash->success(__('Ipo company successfully added.'));
            } else {
                $this->Flash->error(__('Ipo company was not added.'));
            }

            $this->redirect(['_name'=>'all_ipo_companies']);
        }

        $this->loadModel('IpoMarket');

        $all_ipo_markets = $this->IpoMarket
            ->find('all', [
                'fields' => ['id', 'name']
            ])
            ->toArray();

        $this->set(compact('all_ipo_markets'));

    }

    /**
     * edit method this method will edit an IPO company.
     *
     * @param integer $id
     * @return void
     */
    public function edit($id)
    {
        if (empty($id)) {
            $this->redirect(['_name' => 'all_ipo_companies']);
        }
        $this->loadModel('IpoCompany');
        if ($this->request->is('post') && !is_null($this->request->getData())) {

            $result = $this->IpoCompany->editIpoCompany($this->request->data('contact'), $id);

            if ($result) {
                $this->Flash->success(__('Ipo company successfully edited.'));
            } else {
                $this->Flash->error(__('Ipo company was not edited.'));
            }

            $this->redirect(['_name'=>'all_ipo_companies']);
        }

        $ipoCompany = $this->IpoCompany->get($id);
        $this->set(compact('ipoCompany'));

        $this->loadModel('IpoMarket');

        $all_ipo_markets = $this->IpoMarket
            ->find('all', [
                'fields' => ['id', 'name']
            ])
            ->toArray();

        $this->set(compact('all_ipo_markets'));

    }

    /**
     * delete method this method will delete an IPO company.
     *
     * @param integer $id
     * @return void
     */
    public function delete($id)
    {
        $this->loadModel('IpoCompany');
        $result = $this->IpoCompany->deleteIpoCompany($id);

        if ($result) {
            $this->Flash->success(__('Ipo company successfully deleted.'));
        } else {
            $this->Flash->error(__('Ipo company was not deleted.'));
        }

        $this->redirect(['_name'=>'all_ipo_companies']);
    }

    /**
     * disable method this method will disable an IPO company.
     *
     * @param integer $id
     * @return void
     */
    public function disable($id)
    {

        $this->loadModel('IpoCompany');
        $result = $this->IpoCompany->disableIpoCompany($id);

        if ($result) {
            $this->Flash->success(__('Ipo company is disabled.'));
        } else {
            $this->Flash->error(__('Ipo company was not disabled.'));
        }

        $this->redirect(['_name'=>'all_ipo_companies']);
    }
    /**
     * enable method this method will disable an IPO company.
     *
     * @param integer $id
     * @return void
     */
    public function enable($id)
    {

        $this->loadModel('IpoCompany');
        $result = $this->IpoCompany->enableIpoCompany($id);

        if ($result) {
            $this->Flash->success(__('Ipo company is enabled.'));
        } else {
            $this->Flash->error(__('Ipo company was not enabled.'));
        }

        $this->redirect(['_name'=>'all_ipo_companies']);
    }

    /**
     * interests method this method will list all users who are interersted in IPO company.
     *
     * @return void
     */
    public function interests()
    {
        $this->loadModel('IpoInterestedUsers');
        $interests = $this->paginate($this->IpoInterestedUsers->find('all')
            ->contain('IpoCompany')
            ->contain('AppUsers'));

        $this->loadModel('IpoCompany');
        $allIpoCompanies = $this->IpoCompany->find('all')->toArray();
        
        $this->set(compact('allIpoCompanies', 'interests'));

    }

    /**
     * interestsfilter method this method will filter interested users.
     *
     * @param integer $companyId optional parameter for filtering
     * @param integer $experienceId optional parameter for filtering
     * @return void|view
     */
    public function interestsfilter($companyId = -1, $experienceId = -1)
    {
        $this->loadModel('IpoInterestedUsers');
        $interests = $this->IpoInterestedUsers->find('all')
            ->contain('IpoCompany')
            ->contain('AppUsers');

        if($companyId != -1)
            $interests = $interests->where([
                'ipo_company_id' => $companyId
            ]); 

        if($experienceId != -1)
            $interests = $interests->where([
                'AppUsers.experince_id' => $experienceId
            ]);

        $interests = $interests->toArray();
        $this->set(compact('interests'));
        return $this->render('company_filter');
    }

    /**
     * interestsStats method this method will list all IPO companies and the count of interersted users.
     *
     * @return void
     */
    public function interestsStats()
    {      
        $this->loadModel('IpoInterestedUsers');
        $allIpoCompanies = $this->IpoInterestedUsers->find('all', 
            array(
                'fields' => array('interesting_count'=>'COUNT(IpoInterestedUsers.id)', 'ipo_company_name'=>'name'),
                'group' => 'IpoCompany.id',
                'contain' => array('IpoCompany')
           )
        )->all();

        $this->set(compact('allIpoCompanies'));
    }

}
