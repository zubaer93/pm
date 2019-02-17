<?php

namespace App\Model\DataTable;

use Cake\Routing\Router;
use App\Model\Service\Core;
use Cake\ORM\TableRegistry;

class BuySellBrokerDataTable
{

    public function ajaxManageBuySellBrokerSearch($requestData)
    {
        $BuySellBroker = TableRegistry::get('BuySellBroker');
        $brokers = $BuySellBroker->find('all')
                ->contain(['Companies' => function ($q)
                    {
                        return $q->autoFields(false);
                    }])
                ->contain(['Brokers' => function ($q)
            {
                return $q->autoFields(false);
            }]);

        $detail = $brokers;
        $count = $brokers->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        $this->autoRender = false;

        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                    ->where([
                'OR' => [
                    ['(BuySellBroker.id) LIKE' => '%' . $search . '%'],
                    ['(Companies.name) LIKE' => '%' . $search . '%'],
                    ['(Brokers.first_name) LIKE' => '%' . $search . '%'],
                    ['(BuySellBroker.status) LIKE' => '%' . $search . '%'],
                    ['(BuySellBroker.created_at) LIKE' => '%' . $search . '%']
            ]]);
            $brokers_count = $detail;
            $totalFiltered = $brokers_count->count();
        }

        $columns = [
            'BuySellBroker.id',
            'Companies.name',
            'Brokers.first_name',
            'BuySellBroker.status',
            'BuySellBroker.created_at'
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
        $data = array();
        foreach ($results as $row) {
            $edit = Router::url(['_name' => 'edit_buy_sell_broker', 'id' => $row["id"]]);
            $delete = Router::url(['_name' => 'delete_buy_sell_broker', 'id' => $row["id"]]);
            $nestedData = [];
            $nestedData[] = $row["id"];
            $nestedData[] = $row['company']["name"];
            $nestedData[] = $row['broker']["first_name"];
            $nestedData[] = $row['status'];
            $nestedData[] = '<a href="' . $edit . '"'
                    . ' class="edit btn btn-3d btn-sm btn-reveal btn-success">'
                    . '<i class="glyphicon glyphicon-pencil"></i>'
                    . '<span>Edit</span>'
                    . '</a>';
            $nestedData[] = '<button class="btn btn-danger btn-3d btn-sm" '
                    . 'data-toggle="modal" data-target="#confirm-delete" '
                    . 'data-name="' . $row['company']["name"] . '" '
                    . 'data-url="' . $delete . '" '
                    . 'type="submit">'
                    . 'Delete'
                    . '</button>';
            $data[] = $nestedData;
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
