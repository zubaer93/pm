<?php

namespace Api\Model\DataTable;

use Cake\Routing\Router;
use Api\Model\Service\Core;
use Cake\ORM\TableRegistry;

class OrderDataTable
{

    public function ajaxOrderSearch($requestData, $userId)
    {
        $orderType = \Api\Model\Service\Core::$orderType;
        $statusCore = \Api\Model\Service\Core::$status;

        $Transactions = TableRegistry::get('Api.Transactions');

        $orders = $Transactions->find('all')
                ->where(['Transactions.user_id' => $userId])
                ->where(['Transactions.status !=' => 0])
                ->where(['Transactions.status !=' => 4])
                ->contain('Companies')
                ->contain('BrokersList');

        $detail = $orders;
        $count = $orders->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        $this->autoRender = false;


        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];

            $detail = $detail
                    ->where([
                'OR' => [
                    ['(Transactions.market) LIKE' => '%' . $search . '%'],
                    ['(Transactions.client_name) LIKE' => '%' . $search . '%'],
                    ['(Transactions.order_type) LIKE' => '%' . $search . '%'],
                    ['(Transactions.quantity_to_buy) LIKE' => '%' . $search . '%'],
                    ['(Transactions.status) LIKE' => '%' . $search . '%'],
                    ['(Companies.name) LIKE' => '%' . $search . '%'],
                    ['(Companies.symbol) LIKE' => '%' . $search . '%'],
                    ['(BrokersList.first_name) LIKE' => '%' . $search . '%'],
                    ['(BrokersList.last_name) LIKE' => '%' . $search . '%'],
            ]]);

            $company_count = $detail;
            $totalFiltered = $company_count->count();
        }
        $columns = array(
            0 => 'Companies.name',
            1 => 'Companies.symbol',
            2 => 'Transactions.price',
            3 => 'Transactions.client_name',
            4 => 'BrokersList.first_name',
            5 => 'Transactions.order_type',
            6 => 'Transactions.fees',
            7 => 'Transactions.quantity_to_buy',
            8 => 'Transactions.market',
            9 => 'Transactions.action',
            10 => 'Transactions.total',
            11 => 'Transactions.created_at'
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
            foreach ($orderType as $key => $type) {
                if ($row["order_type"] === $key) {
                    $order = $type;
                }
            }
            foreach ($statusCore as $key => $status) {
                if ($row["status"] === $key) {
                    $statusTable = $status;
                }
            }
            if ($row["status"] === 1) {
                $class = 'badge-info';
            } elseif ($row["status"] === 2) {
                $class = 'badge-success';
            } elseif ($row["status"] === 3) {
                $class = 'badge-danger';
            }
            $nestedData = [];
            $nestedData[] = $row["company"]["name"];
            $nestedData[] = $row["company"]["symbol"];
            $nestedData[] = $row["price"];
            $nestedData[] = $row["client_name"];
            $nestedData[] = $row["brokers_list"]["first_name"];
            $nestedData[] = $order;
            $nestedData[] = $row["fees"];
            $nestedData[] = $row["quantity_to_buy"];
            $nestedData[] = $row["market"];
            $nestedData[] = $row["action"];
            $nestedData[] = $row["total"];
            $nestedData[] = $row["created_at"]->nice();
            $nestedData[] = '<span class="badge ' . $class . '">' . $statusTable . '</span>';
            $data[] = $nestedData;
            $i++;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return $json_data;
    }

}
