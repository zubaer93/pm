<?php

namespace App\Controller\Admin;

use Cake\ORM\TableRegistry;
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

    const JMD = 'JMD';

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        
    }

    /**
     * 
     */
    public function ajaxManageFinancialStatementSearch()
    {
        $requestData = $this->request->getData();

        $obj = new \App\Model\DataTable\FinancialStatementDataTable();
        $result = $obj->ajaxManageFinancialStatementSearch($requestData);

        echo $result;
        exit;
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
        if ($this->request->is('post')) {
            $financialStatement = $this->FinancialStatement->setStatement($this->request->getData());
            $this->Flash->success(__('The financial statement has been saved.'));
            $this->redirect(['_name' => 'financial_statement']);
        }
        $Companies = TableRegistry::get('Companies');
        $all_companies = $Companies->find('list', array('fields' => array('name', 'id')))
                ->toArray();

        $this->set(compact('all_companies'));
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
            'contain' => ['FinancialStatementFiles']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->filterUploadedFile($this->request->getData());
            $financialStatement = $this->FinancialStatement->editStatement($data, $id);
            $this->Flash->success(__('The financial statement has been saved.'));

            return $this->redirect(['_name' => 'financial_statement']);
        }

//        $language = self::JMD;
        $this->loadModel('Companies');
        $all_companies = $this->Companies->find('list')->toList();
        $this->set(compact('financialStatement', 'all_companies'));
        $this->set('_serialize', ['financialStatement']);
    }

    public function filterUploadedFile($data)
    {
        if (isset($data['file']) && isset($data['file_name_admin'])) {
            foreach ($data['file'] as $key => $val) {
                if (array_search($val['name'], $data['file_name_admin']) !== false) {
                    unset($data['file'][$key]);
                }
            }
        }
        return $data;
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
//        $this->request->allowMethod(['post', 'delete']);
        $financialStatement = $this->FinancialStatement->get($id);
        if (!is_null($financialStatement)) {
            $this->loadModel('FinancialStatementFiles');
            $this->FinancialStatementFiles->deleteFiles($id);
        }
        if ($this->FinancialStatement->delete($financialStatement)) {
            $this->Flash->success(__('The financial statement has been deleted.'));
        } else {
            $this->Flash->error(__('The financial statement could not be deleted. Please, try again.'));
        }

        return $this->redirect(['_name' => 'financial_statement']);
    }

}
