<?php

namespace App\Model\DataTable;

use Cake\Routing\Router;
use App\Model\Service\Core;
use Cake\ORM\TableRegistry;

class StockDataTable
{

    public function ajaxManageStocksSearch($requestData)
    {

        $Stocks = TableRegistry::get('Stocks');
        $stocks = $Stocks->find();

        $detail = $stocks;
        
        $count = $stocks->count();
        
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;
        
        $this->autoRender = false;
        if (isset($requestData['search']['value']) && !empty($requestData['search']['value'])) {
            $search = $requestData['search']['value'];
            $detail = $detail
                ->where([
                'OR' => [
                    ['(Stocks.symbol) LIKE' => '%' . $search . '%'],
                    ['(Stocks.id) LIKE' => '%' . $search . '%'],
                    ['(Stocks.last_refreshed) LIKE' => '%' . $search . '%'],
                    ['(Stocks.open) LIKE' => '%' . $search . '%'],
                    ['(Stocks.close) LIKE' => '%' . $search . '%'],
                    ['(Stocks.low) LIKE' => '%' . $search . '%'],
                    ['(Stocks.volume) LIKE' => '%' . $search . '%'],
                    ['(Stocks.high) LIKE' => '%' . $search . '%']
            ]]);
            $stocks_count = $detail;
            $totalFiltered = $stocks_count->count();
        }

        $columns = array(
            0 => 'id',
            1 => 'symbol',
            2 => 'last_refreshed',
            3 => 'open',
            4 => 'high',
            5 => 'low',
            6 => 'close',
            7 => 'volume'
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
            $edit = Router::url(['_name' => 'edit_company_stock', 'id' => $row["id"]]);
            $nestedData = [];
            $nestedData[] = $row["id"];
            $nestedData[] = $row["symbol"];
            $nestedData[] = date('Y-m-d H:i:s', strtotime($row["last_refreshed"]));
            $nestedData[] = $row["open"];
            $nestedData[] = $row["high"];
            $nestedData[] = $row["low"];
            $nestedData[] = $row["close"];
            $nestedData[] = $row["volume"];
            $nestedData[] = '<a href="' . $edit . '"'
                    . ' class="edit btn btn-3d btn-sm btn-reveal btn-success">'
                    . '<i class="glyphicon glyphicon-pencil"></i>'
                    . '<span>Edit</span>'
                    . '</a>';
            $nestedData[] = '<button class="btn btn-danger btn-3d btn-sm" '
                    . 'data-toggle="modal" data-target="#confirm-delete" '
                    . 'data-name="' . $row["symbol"] . '" '
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
