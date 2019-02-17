<?php

namespace App\Model\DataTable;

use Cake\Routing\Router;
use App\Model\Service\Core;
use Cake\ORM\TableRegistry;

class AnalysisDataTable
{

    public function ajaxManageAnalysisTimeAndSalesSearch($requestData, $lng)
    {
        $Companies = TableRegistry::get('Companies');
        $company_id = $Companies->getCompanyId($requestData['symbol'], $lng);

        $Stocks = TableRegistry::get('Stocks');
        $stocks = $Stocks->getAllStocksFromCompanyId($company_id);
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
                    ['(Stocks.volume) LIKE' => '%' . $search . '%'],
                    ['(Stocks.quantity) LIKE' => '%' . $search . '%'],
                    ['(Stocks.open) LIKE' => '%' . $search . '%'],
                    ['(Stocks.last_refreshed) LIKE' => '%' . $search . '%'],
            ]]);
            $stocks_count = $detail;
            $totalFiltered = $stocks_count->count();
        }

        $columns = array(
            0 => 'Stocks.open',
            2 => 'Stocks.quantity',
            3 => 'Stocks.volume',
            4 => 'Stocks.last_refreshed',
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
            $close = $row['open'] + (-1 * $row['price_change']);

            if ($row['open'] - $close >= 0) {
                $class = "positive";
            } else {
                $class = "negative";
            }
            $nestedData = [];
            $nestedData[] = '$' . $row['open'];
            $nestedData[] = '<span class="change-image-arrow ' . $class . '"> </span>';
            $nestedData[] = $row['quantity'];
            $nestedData[] = $row['volume'];
            $nestedData[] = date('Y-m-d H:i:s', strtotime($row["last_refreshed"]));

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
