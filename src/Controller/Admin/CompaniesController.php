<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;

class CompaniesController extends AppController
{
    /**
     * get Company info and set variable userId based on logged-in user
     *
     * @param $symbol string company symbol
     * @return mixed
     */
    public function index()
    {
        $this->redirect(['_name' => 'users_list']);
    }

    /**
     * add method it will add a new Company.
     *
     * @return void
     */
    public function add()
    {
        $company = $this->Companies->newEntity();
        if ($this->request->is('post')) {
            $company = $this->Companies->patchEntity($company, $this->request->getData(), [
                'associated' => ['KeyPeople', 'Affiliates']
            ]);
            if ($this->Companies->save($company)) {
                $this->Flash->success(__('Company successfully added.'));
                $this->redirect(['_name' => 'all_company']);
            } else {
                $this->Flash->error(__('Company was not added.'));
            }
        }

        $exchanges = $this->Companies->Exchanges->find('list');
        $affiliates = $this->Companies->Affiliates->find('list');

        $this->set(compact('exchanges', 'company', 'affiliates'));
        $this->set('_serialize', ['exchanges', 'company', 'affiliates']);
    }

    /**
     * edit method it will edit an existing Company.
     *
     * @param int $id Company id
     * @return void
     */
    public function edit($id)
    {
        $company = $this->Companies->get($id, [
            'contain' => ['KeyPeople', 'Affiliates']
        ]);
        if ($this->request->is('post') || $this->request->is('put')) {
            $company = $this->Companies->patchEntity($company, $this->request->getData(), [
                'associated' => ['KeyPeople', 'Affiliates']
            ]);

            if ($this->Companies->save($company)) {
                $this->Flash->success(__('Company successfully edited.'));
                $this->redirect(['_name' => 'all_company']);
            } else {
                $this->Flash->error(__('Company was not edited.'));
            }
        }

        $exchanges = $this->Companies->Exchanges->find('list');
        $affiliates = $this->Companies->Affiliates->find('list');

        $this->set(compact('exchanges', 'company', 'affiliates'));
        $this->set('_serialize', ['exchanges', 'company', 'affiliates']);
    }

    /**
     * delete method it will delete a Company.
     *
     * @param int $id Company id
     * @return void
     */
    public function delete($id)
    {
        $company = $this->Companies->get($id);

        if ($this->Companies->trash($result)) {
            $this->Flash->success(__('Company successfully deleted.'));
        } else {
            $this->Flash->error(__('Company was not deleted.'));
        }

        $this->redirect(['_name' => 'all_company']);
    }

    public function all()
    {  
    }

    public function getCompany()
    {
        $requestMarket = $this->request->getQuery('market');
        $search = $this->request->getQuery('search');

        $allCompany = $this->Companies->getSearchCompanyWithLang($requestMarket, $search);
        $response = [
            'status' => 'success',
            'allCompony' => $allCompany,
            'message' => 'Successful!'
        ];

        $this->setJsonResponse($response);
        return $this->response;
    }

    protected function setJsonResponse($response)
    {
        $response = json_encode($response);
        $this->response->type('application/json');
        $this->response->body($response);
    }

    public function importAll()
    {
        if ($this->request->is('post') && !is_null($this->request->getData())) {
            try {
                $this->loadModel('Stocks');

                $path = $this->request->data['file']['tmp_name'];

                $options = [
                    'length' => 0,
                    'delimiter' => ',',
                    'enclosure' => '"',
                    'escape' => '\\',
                    'headers' => true,
                    'text' => false,
                    'excel_bom' => false,
                ];
                $data = $this->Companies->importCsv($path, null, $options);
                $i = 0;
                foreach ($data as $val) {
                    $id = $this->Companies->saveOrUpdateCsvCompany($val);
                    $bool = $this->Stocks->saveOrUpdateCsvOne($val, $id);
                    if (!$bool) {
                        $i++;
                    }
                }
                if (!$i) {
                    $this->Flash->success(__('Successfully imported.'));
                } else {
                    $this->Flash->error(__('There were some problems, maybe not all have been imported'));
                }
            } catch (\Exception $e) {
                $this->Flash->error(__('There were some problems, maybe not all have been imported'));
            }
        }

        $this->redirect(['_name' => 'all_company']);
    }

    public function disable($id)
    {
        $result = $this->Companies->disableCompany($id);

        if ($result) {
            $this->Flash->success(__('Company is disabled.'));
        } else {
            $this->Flash->error(__('Company was not disabled.'));
        }

        $this->redirect(['_name' => 'all_company']);
    }

    public function enable($id)
    {
        $result = $this->Companies->enableCompany($id);

        if ($result) {
            $this->Flash->success(__('Company is enabled.'));
        } else {
            $this->Flash->error(__('Company was not enabled.'));
        }

        $this->redirect(['_name' => 'all_company']);
    }

    public function ajaxManageCompanySearch()
    {
        $requestData = $this->request->getData();

        $obj = new \App\Model\DataTable\CompanyDataTable();
        $result = $obj->ajaxManageCompanySearch($requestData);

        echo $result;
        exit;
    }
}
