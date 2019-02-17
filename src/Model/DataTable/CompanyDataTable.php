<?php

namespace App\Model\DataTable;

use Cake\Routing\Router;
use App\Model\Service\Core;
use Cake\ORM\TableRegistry;

class CompanyDataTable
{

    public function ajaxManageCompanySearch($requestData, $language = null)
    {
        $Companies = TableRegistry::get('Companies');
        $companies = $Companies->find()
                ->contain(['Exchanges' => function ($q)
            {
                return $q->autoFields(false)
                        ->contain(['Countries']);
            }]);

        $detail = $companies;
        
        $count = $companies->count();
        
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        $this->autoRender = false;

        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(Companies.name) LIKE' => '%' . $search . '%'],
                    ['(Companies.id) LIKE' => '%' . $search . '%'],
                    ['(Companies.symbol) LIKE' => '%' . $search . '%'],
                    ['(Companies.sector) LIKE' => '%' . $search . '%'],
                    ['(Companies.industry) LIKE' => '%' . $search . '%'],
                    ['(Companies.exchange_id) LIKE' => '%' . $search . '%'],
                    ['(Countries.market) LIKE' => '%' . $search . '%']
            ]]);
            $company_count = $detail;
            $totalFiltered = $company_count->count();
        }

        $columns = array(
            0 => 'Companies.id',
            1 => 'Companies.name',
            2 => 'Companies.symbol',
            3 => 'Companies.ipoyear',
            4 => 'Companies.sector',
            5 => 'Companies.industry',
            6 => 'Companies.exchange_id',
            7 => 'Countries.market'
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
            $checked = (!$row['enable']) ? "checked" : "";
            if ($row['enable']) {
                $enable = Router::url(['_name' => 'enable_company', 'id' => $row["id"]]);
            } else {
                $enable = Router::url(['_name' => 'disable_company', 'id' => $row["id"]]);
            }

            $import = Router::url(['_name' => 'import_stock', 'id' => $row["id"]]);
            $edit = Router::url(['_name' => 'edit_company', 'id' => $row["id"]]);
            $nestedData = [];
            $nestedData[] = $row["id"];
            $nestedData[] = $row["name"];
            $nestedData[] = $row["symbol"];
            $nestedData[] = $row["ipoyear"];
            $nestedData[] = $row["sector"];
            $nestedData[] = $row["industry"];
            $nestedData[] = $row["exchange_id"];
            $nestedData[] = $row["exchange"]['country']['market'];
            $nestedData[] = '<label class="switch switch-info btn-sm">
                                        <input class="disable" onchange="window.location.assign(&quot;' . $enable . '&quot;)" type="checkbox"' . $checked . ' >
                                        <span class="switch-label" data-on="YES" data-off="NO"></span>
                                    </label>';
            $nestedData[] = '<a href="' . $import . '"'
                    . ' class="edit btn btn-3d btn-sm btn-reveal btn-yellow">'
                    . '<i class="glyphicon glyphicon-upload"></i>'
                    . '<span>Import</span>'
                    . '</a>';
            $nestedData[] = '<a href="' . $edit . '"'
                    . ' class="edit btn btn-3d btn-sm btn-reveal btn-success">'
                    . '<i class="glyphicon glyphicon-pencil"></i>'
                    . '<span>Edit</span>'
                    . '</a>';
            $nestedData[] = '<button class="btn btn-danger btn-3d btn-sm" '
                    . 'data-toggle="modal" data-target="#confirm-delete" '
                    . 'data-name="' . $row["name"] . '" '
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
