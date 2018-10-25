<?php

namespace Api\Model\DataTable;

use Cake\Routing\Router;
use Api\Model\Service\Core;
use Cake\ORM\TableRegistry;

class StockDetailsDataTable
{

    public function ajaxManageStockDetailsSearch($requestData)
    {
        $Stocks = TableRegistry::get('Api.StocksDetails');
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
                    ['(StocksDetails.high_price_52_week) LIKE' => '%' . $search . '%'],
                    ['(StocksDetails.low_price_52_week) LIKE' => '%' . $search . '%'],
                    ['(StocksDetails.days_high_price) LIKE' => '%' . $search . '%'],
                    ['(StocksDetails.days_low_price) LIKE' => '%' . $search . '%'],
                    ['(StocksDetails.close_price) LIKE' => '%' . $search . '%'],
                    ['(StocksDetails.last_traded_price) LIKE' => '%' . $search . '%'],
                    ['(StocksDetails.bid_price) LIKE' => '%' . $search . '%'],
                    ['(StocksDetails.ask_price) LIKE' => '%' . $search . '%'],
                    ['(StocksDetails.trade_value) LIKE' => '%' . $search . '%'],
                    ['(StocksDetails.market_cap) LIKE' => '%' . $search . '%'],
                    ['(StocksDetails.totalissuedshares) LIKE' => '%' . $search . '%'],
                    ['(Companies.name) LIKE' => '%' . $search . '%']
            ]]);
            $stocks_count = $detail;
            $totalFiltered = $stocks_count->count();
        }

        $columns = array(
            0 => 'Companies.name',
            1 => 'StocksDetails.high_price_52_week',
            2 => 'StocksDetails.low_price_52_week',
            3 => 'StocksDetails.days_high_price',
            4 => 'StocksDetails.days_low_price',
            5 => 'StocksDetails.last_traded_price',
            6 => 'StocksDetails.bid_price',
            7 => 'StocksDetails.ask_price',
            8 => 'StocksDetails.trade_value',
            9 => 'StocksDetails.market_cap',
            10 => 'StocksDetails.totalissuedshares',
        );

        $sidx = $columns[$requestData['order'][0]['column']];
        $sord = $requestData['order'][0]['dir'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $page = ($start) ? $start / $length : 1;
        $results = $detail
                ->order($sidx . ' ' . $sord)
                ->limit((int) $length)
                ->page($page)
                ->contain('Companies');
        $i = 0;
        $data = array();
        foreach ($results as $row) {
            $edit = Router::url(['_name' => 'edit_stock_details', 'id' => $row["id"]]);
            $nestedData = [];
            $nestedData[] = $row["company"]['name'];
            $nestedData[] = $row["high_price_52_week"];
            $nestedData[] = $row["low_price_52_week"];
//            $nestedData[] = date('Y-m-d H:i:s', strtotime($row["last_refreshed"]));
            $nestedData[] = $row["days_high_price"];
            $nestedData[] = $row["days_low_price"];
            $nestedData[] = $row["last_traded_price"];
            $nestedData[] = $row["bid_price"];
            $nestedData[] = $row["ask_price"];
            $nestedData[] = $row["trade_value"];
            $nestedData[] = $row["market_cap"];
            $nestedData[] = $row["totalissuedshares"];
            $nestedData[] = '<a href="' . $edit . '"'
                    . ' class="edit btn btn-3d btn-sm btn-reveal btn-success">'
                    . '<i class="glyphicon glyphicon-pencil"></i>'
                    . '<span>Edit</span>'
                    . '</a>';
            $nestedData[] = '<button class="btn btn-danger btn-3d btn-sm" '
                    . 'data-toggle="modal" data-target="#confirm-delete" '
                    . 'data-name="' . $row["company"]['name'] . '" '
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
