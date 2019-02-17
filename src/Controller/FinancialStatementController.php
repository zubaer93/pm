<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * FinancialStatement Controller
 *
 * @property \App\Model\Table\FinancialStatementTable $FinancialStatement
 *
 * @method \App\Model\Entity\FinancialStatement[] paginate($object = null, array $settings = [])
 */
class FinancialStatementController extends AppController
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->components()->unload('Csrf');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {

        $this->paginate = [
            'contain' => ['Companies'],
            'limit' => 10
        ];
        $financialStatement = $this->paginate($this->FinancialStatement);
        $this->set(compact('financialStatement'));
        $this->set('_serialize', ['financialStatement']);
    }

    /**
     * View method
     *
     * @param string|null $id Financial Statement id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function symbol($symbol_id)
    {
        $data = explode('_', $symbol_id);
        $symbol = '';
        $id = '';

        if (isset($data[0])) {
            $symbol = $data[0];
        }
        if (isset($data[1])) {
            $id = $data[1];
        }
        $this->loadModel('Companies');
        $financialStatement = $this->FinancialStatement->get($id, [
            'contain' => ['Companies', 'FinancialStatementFiles'],
        ]);

        $this->set('financialStatement', $financialStatement);
        $this->set('_serialize', ['financialStatement']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $financialStatement = $this->FinancialStatement->newEntity();
        if ($this->request->is('post')) {
            $financialStatement = $this->FinancialStatement->patchEntity($financialStatement, $this->request->getData());
            if ($this->FinancialStatement->save($financialStatement)) {
                $this->Flash->success(__('The financial statement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The financial statement could not be saved. Please, try again.'));
        }
        $companies = $this->FinancialStatement->Companies->find('list', ['limit' => 200]);
        $this->set(compact('financialStatement', 'companies'));
        $this->set('_serialize', ['financialStatement']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Financial Statement id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $financialStatement = $this->FinancialStatement->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $financialStatement = $this->FinancialStatement->patchEntity($financialStatement, $this->request->getData());
            if ($this->FinancialStatement->save($financialStatement)) {
                $this->Flash->success(__('The financial statement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The financial statement could not be saved. Please, try again.'));
        }
        $companies = $this->FinancialStatement->Companies->find('list', ['limit' => 200]);
        $this->set(compact('financialStatement', 'companies'));
        $this->set('_serialize', ['financialStatement']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Financial Statement id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $financialStatement = $this->FinancialStatement->get($id);
        if ($this->FinancialStatement->delete($financialStatement)) {
            $this->Flash->success(__('The financial statement has been deleted.'));
        } else {
            $this->Flash->error(__('The financial statement could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    /**
     * ajaxManageFinancialStatementsSearch dataTable
     * @param type $symbol
     */
    public function ajaxManageFinancialStatementsSearch($symbol)
    {
        if ($this->request->is('ajax')) {
            $requestData = $this->request->getData();
            $obj = new \App\Model\DataTable\FinancialStatementFrontDataTable();
            $result = $obj->ajaxManageFinancialStatementsSearch($requestData, $symbol, $this->_getCurrentLanguage());

            echo $result;
            exit;
        } else {
            return $this->redirect(['_name' => 'home']);
        }
    }

}
