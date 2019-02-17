<?php

namespace App\Model\DataTable;

use Cake\Routing\Router;
use App\Model\Service\Core;
use Cake\ORM\TableRegistry;

class FinancialStatementFrontDataTable
{

    /**
     * 
     * @param type $requestData
     * @param type $symbol
     * @return type
     */
    public function ajaxManageFinancialStatementsSearch($requestData, $symbol, $language)
    {
        $FinancialStatement = TableRegistry::get('FinancialStatement');
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

        $this->autoRender = false;
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

        $sidx = $columns[$requestData['order'][0]['column']];
        $sord = $requestData['order'][0]['dir'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $page = ($start) ? $start / $length : 1;
        $results = $detail
                ->order($sidx . ' ' . $sord)
                ->limit((int) $length)
                ->page($page);
        $i = 0;
        $data = array();
        foreach ($results as $row) {
            $nestedData = [];
            $nestedData[] = '<a href="' . Router::url(['_name' => 'financial_statements_symbol', 'symbol' => $row["company"]['symbol'].'_'.$row['id']]) . '" class="tab-post-link">' . $row["title"] . '</a>';
            $nestedData[] = '<a href="' . Router::url(['_name' => 'financial_statements_symbol', 'symbol' => $row["company"]['symbol'].'_'.$row['id']]) . '" class="tab-post-link">' . $row['company']["name"] . '</a>';
            $nestedData[] = '<a href="' . Router::url(['_name' => 'financial_statements_symbol', 'symbol' => $row["company"]['symbol'].'_'.$row['id']]) . '" class="tab-post-link">' . $row["created_at"]->nice() . '</a>';
            
            $data[] = $nestedData;
            $i++;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return json_encode($json_data);
    }

}
