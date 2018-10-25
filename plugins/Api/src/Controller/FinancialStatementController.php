<?php

namespace Api\Controller;

use Api\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

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
     * ajaxManageFinancialStatementsSearch dataTable
     * @param type $symbol
     */
    public function companyFinancial($symbol)
    {
        $language = $this->currentLanguage;
        $FinancialStatement = TableRegistry::get('Api.FinancialStatement');
        $statement = $FinancialStatement->find('all')
                ->contain(['Companies' => function ($q) use ($language, $symbol)
            {
                return $q->autoFields(false)
                        ->where(['Companies.symbol' => $symbol])
                        ->contain(['Exchanges' => function ($q) use ($language)
                            {
                                return $q->autoFields(false)
                                        ->contain(['Countries' => function ($q) use ($language)
                                            {
                                                return $q->autoFields(false)
                                                        ->where(['Countries.market' => $language]);
                                            }]);
                            }]);
            }]);
        $detail = $statement;
        $count = $statement->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;

        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(FinancialStatement.title) LIKE' => '%' . $search . '%']
            ]]);
            $statement_count = $detail;
            $totalFiltered = $statement_count->count();
        }

        $columns = array(
            0 => 'FinancialStatement.title',
            1 => 'Companies.name',
            2 => 'FinancialStatement.created_at'
        );

        $sidx =  $columns[0];
        $sord =  'asc';
        // $start = $requestData['start'];
        // $length = $requestData['length'];
        // $page = ($start) ? $start / $length : 1;
        $results = $detail
                ->order($sidx . ' ' . $sord);
                // ->limit((int) $length)
                // ->page($page);
        $i = 0;
        $data = array();
        foreach ($results as $row) {
            $nestedData = [];
            $nestedData['tite'] = $row["title"];
            $nestedData['link'] = Router::url(['_name' => 'financial_statements_symbol', 'symbol' => $row["company"]['symbol'].'_'.$row['id']]);
            $nestedData['company_name'] = $row['company']["name"];
            $nestedData['created_at'] = $row["created_at"]->nice();
            
            $data[] = $nestedData;
            $i++;
        }
        $json_data = array(
            "data" => $data,
        );
        $this->apiResponse = $json_data;
    }

    public function companyFinancialModal($symbol)
    {
        $language = $this->currentLanguage;
        $FinancialStatement = TableRegistry::get('Api.FinancialStatement');
        $statement = $FinancialStatement->find('all')
                ->contain(['Companies' => function ($q) use ($language, $symbol)
            {
                return $q->autoFields(false)
                        ->where(['Companies.symbol' => $symbol])
                        ->contain(['Exchanges' => function ($q) use ($language)
                            {
                                return $q->autoFields(false)
                                        ->contain(['Countries' => function ($q) use ($language)
                                            {
                                                return $q->autoFields(false)
                                                        ->where(['Countries.market' => $language]);
                                            }]);
                            }]);
            }]);
        $detail = $statement;
        $count = $statement->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;

        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(FinancialStatement.title) LIKE' => '%' . $search . '%']
            ]]);
            $statement_count = $detail;
            $totalFiltered = $statement_count->count();
        }

        $columns = array(
            0 => 'FinancialStatement.title',
            1 => 'Companies.name',
            2 => 'FinancialStatement.created_at'
        );

        $sidx =  $columns[0];
        $sord =  'asc';
        $results = $this->paginate($detail
                ->order($sidx . ' ' . $sord));

        $paginate = $this->Paginator->configShallow(['data' => $results])->request->getParam('paging')['FinancialStatement'];
        $data = array();
        foreach ($results as $row) {
            $nestedData = [];
            $nestedData['title'] = $row["title"];
            $nestedData['link'] = Router::url(['_name' => 'financial_statements_symbol', 'symbol' => $row["company"]['symbol'].'_'.$row['id']]);
            $nestedData['company_name'] = $row['company']["name"];
            $nestedData['created_at'] = $row["created_at"]->nice();
            
            $data[] = $nestedData;
        }
        $json_data = array(
            "data" => $data,
            "paginate" => $paginate
        );
        $this->apiResponse = $json_data;
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
        $this->loadModel('Api.Companies');
        $this->loadModel('Api.FinancialStatementFiles');
        $financialStatement = $this->FinancialStatement->get($id, [
            'contain' => ['Companies', 'FinancialStatementFiles'],
        ]);
        
        foreach($financialStatement['financial_statement_files'] as $fs){
            $fs['link'] = Router::url(\Cake\Core\Configure::read('Users.financial.path_sm').$fs['file_path'], true);
        }
        $this->apiResponse['data'] = $financialStatement;
        
    }

}
