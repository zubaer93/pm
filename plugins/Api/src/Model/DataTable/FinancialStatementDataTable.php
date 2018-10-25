<?php

namespace Api\Model\DataTable;

use Cake\Routing\Router;
use Api\Model\Service\Core;
use Cake\ORM\TableRegistry;

class FinancialStatementDataTable
{

    public function ajaxManageFinancialStatementSearch($requestData)
    {
        $FinancialStatement = TableRegistry::get('Api.FinancialStatement');
        $financialStatement = $FinancialStatement->find('all')
                ->contain(['Companies' => function ($q)
            {
                return $q->autoFields(false);
            }]);
        $detail = $financialStatement;
        
        $count = $financialStatement->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        $this->autoRender = false;

        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(FinancialStatement.id) LIKE' => '%' . $search . '%'],
                    ['(FinancialStatement.title) LIKE' => '%' . $search . '%'],
                    ['(FinancialStatement.description) LIKE' => '%' . $search . '%'],
                    ['(Companies.name) LIKE' => '%' . $search . '%']
            ]]);
            $financialStatement_count = $detail;
            $totalFiltered = $financialStatement_count->count();
        }

        $columns = array(
            0 => 'FinancialStatement.id',
            1 => 'FinancialStatement.title',
            2 => 'FinancialStatement.description',
            3 => 'Companies.name'
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
//            $checked = (!$row['enable']) ? "checked" : "";
//            if ($row['enable']) {
//                $enable = Router::url(['_name' => 'enable_company', 'id' => $row["id"]]);
//            } else {
//                $enable = Router::url(['_name' => 'disable_company', 'id' => $row["id"]]);
//            }
            $edit = Router::url(['_name' => 'edit_financial_statement', 'id' => $row["id"]]);
            $nestedData = [];
            $nestedData[] = $row["id"];
            $nestedData[] = $row["title"];
            $nestedData[] = $row["description"];
            $nestedData[] = $row["company"]['name'];
//            $nestedData[] = '<a href="' . $import . '"'
//                    . ' class="edit btn btn-3d btn-sm btn-reveal btn-yellow">'
//                    . '<i class="glyphicon glyphicon-upload"></i>'
//                    . '<span>Import</span>'
//                    . '</a>';
            $nestedData[] = '<a href="' . $edit . '"'
                    . ' class="edit btn btn-3d btn-sm btn-reveal btn-success">'
                    . '<i class="glyphicon glyphicon-pencil"></i>'
                    . '<span>Edit</span>'
                    . '</a>';
            $nestedData[] = '<button class="btn btn-danger btn-3d btn-sm" '
                    . 'data-toggle="modal" data-target="#confirm-delete" '
                    . 'data-name="' . $row["title"] . '" '
                    . 'data-url="delete/' . $row["id"] . '" '
                    . 'type="submit">'
                    . 'Delete'
                    . '</button>';
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
