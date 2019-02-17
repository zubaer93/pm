<?php

namespace App\Model\DataTable;

use Cake\Routing\Router;
use App\Model\Service\Core;
use Cake\ORM\TableRegistry;

class AffiliatesDataTable
{

    public function ajaxManageAffiliatesSearch($requestData, $language = null)
    {
        $Affiliates = TableRegistry::get('Affiliates');
        $affiliates = $Affiliates->find();

        $detail = $affiliates;
        
        $count = $affiliates->count();
        
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        $this->autoRender = false;

        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(Affilates.id) LIKE' => '%' . $search . '%'],
                    ['(Affilates.name) LIKE' => '%' . $search . '%'],
                    ['(Affilates.address) LIKE' => '%' . $search . '%'],
                    ['(Affilates.website) LIKE' => '%' . $search . '%'],
                    ['(Affilates.description) LIKE' => '%' . $search . '%'],
                    ['(Affilates.date_of_incorporation) LIKE' => '%' . $search . '%']
            ]]);
            $affiliate_count = $detail;
            $totalFiltered = $affiliate_count->count();
        }

        $columns = [
            'Affiliates.id',
            'Affiliates.name',
            'Affiliates.address',
            'Affiliates.website',
            'Affiliates.date_of_incorporation'
        ];

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
        $data = [];
        foreach ($results as $row) {
            $edit = Router::url(['_name' => 'edit_affiliate', 'id' => $row["id"]]);
            $nestedData = [];
            $nestedData[] = $row["id"];
            $nestedData[] = $row["name"];
            $nestedData[] = $row["address"];
            $nestedData[] = $row["website"];
            $nestedData[] = $row->showDate;
            $nestedData[] = '<a href="' . $edit . '"'
                    . ' class="edit btn btn-3d btn-sm btn-reveal btn-success">'
                    . '<i class="glyphicon glyphicon-pencil"></i>'
                    . '<span>Edit</span>'
                    . '</a>';
            $nestedData[] = '<button class="btn btn-danger btn-3d btn-sm" '
                    . 'data-toggle="modal" data-target="#confirm-delete" '
                    . 'data-name="' . $row["name"] . '" '
                    . 'data-url="affiliate/delete/' . $row["id"] . '" '
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
