<?php

namespace App\Model\DataTable;

use Cake\Routing\Router;
use App\Model\Service\Core;
use Cake\ORM\TableRegistry;

class CompanyFrontDataTable
{

    const JMD = 'JMD';

    public function ajaxManageCompanySearch($requestData, $language = null)
    {
        $search_input_data = $requestData['search']['value'];
        $search_input_data = (object) json_decode($search_input_data, true);

        $Companies = TableRegistry::get('Companies');
        $companies = $Companies->find('all')
            ->contain(['Exchanges' => function ($q) use ($language) {
                $q = $q->autoFields(false)
                    ->contain(['Countries' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => $language]);
                    }]);
                return $q;
            }])
            ->join(['sd_max' => [
                'table'      => '(SELECT MAX(id) max_id, company_id FROM stocks_details GROUP BY company_id)',
                'type'       => 'LEFT',
                'conditions' => 'Companies.id = sd_max.company_id',
            ]])
            ->join(['sd' => [
                'table'      => 'stocks_details',
                'type'       => 'LEFT',
                'conditions' => 'sd_max.max_id = sd.id',
            ]])
            ->join(['st_max' => [
                'table'      => '(SELECT MAX(id) max_st_id, company_id FROM stocks GROUP BY company_id)',
                'type'       => 'LEFT',
                'conditions' => 'Companies.id = st_max.company_id',
            ]])
            ->join(['st' => [
                'table'      => 'stocks',
                'type'       => 'LEFT',
                'conditions' => 'st_max.max_st_id = st.id',
            ]]);

        $companies->typeMap()
            ->addDefaults([
                'sd.market_cap' => 'integer',
                'sd.dividend_amount' => 'integer',
                'st.open' => 'integer',
                'st.volume' => 'integer',
            ]);


        if (isset($search_input_data->market_cap) && $search_input_data->market_cap) {
            $market_cap_value_exploded = explode('_',$search_input_data->market_cap);
            $from_val = $market_cap_value_exploded[0];
            $to_val = $market_cap_value_exploded[1];
            if($from_val){
                $companies->where(['sd.market_cap >= '=>$from_val]);
            }

            if($to_val){
                $companies->where(['sd.market_cap <= '=>$to_val]);
            }
        }

        if (isset($search_input_data->dividend_yield) && $search_input_data->dividend_yield) {
            $dividend_yield_value_exploded = explode('_',$search_input_data->dividend_yield);
            $from_val = $dividend_yield_value_exploded[0];
            $to_val = $dividend_yield_value_exploded[1];
            if($from_val){
                $companies->where(['sd.dividend_amount >= '=>$from_val]);
            }

            if($to_val){
                $companies->where(['sd.dividend_amount <= '=>$to_val]);
            }
        }

        if (isset($search_input_data->price) && $search_input_data->price) {
            $price_value_exploded = explode('_',$search_input_data->price);
            $from_val = $price_value_exploded[0];
            $to_val = $price_value_exploded[1];
            if($from_val){
                $companies->where(['st.open >= '=>$from_val]);
            }

            if($to_val){
                $companies->where(['st.open <= '=>$to_val]);
            }
        }

        if (isset($search_input_data->current_volume) && $search_input_data->current_volume) {
            $current_volume_value_exploded = explode('_',$search_input_data->current_volume);
            $from_val = $current_volume_value_exploded[0];
            $to_val = $current_volume_value_exploded[1];
            if($from_val){
                $companies->where(['st.volume >= '=>$from_val]);
            }

            if($to_val){
                $companies->where(['st.volume <= '=>$to_val]);
            }
        }

        if (isset($search_input_data->change) && $search_input_data->change) {
            $change_value_exploded = explode('_',$search_input_data->change);
            $from_val = $change_value_exploded[0];
            $to_val = $change_value_exploded[1];
            if($from_val){
                $companies->where(['(st.price_change *100)/st.open >= '=>$from_val]);
            }

            if($to_val){
                $companies->where(['(st.price_change *100)/st.open <= '=>$to_val]);
            }
        }

        if (isset($search_input_data->week_52_high) && $search_input_data->week_52_high) {
            $week_52_high_volume_value_exploded = explode('_',$search_input_data->week_52_high);
            $from_val = $week_52_high_volume_value_exploded[0];
            $to_val = $week_52_high_volume_value_exploded[1];
            if($from_val){
                $companies->where(['sd.high_price_52_week >= '=>$from_val]);
            }

            if($to_val){
                $companies->where(['sd.high_price_52_week <= '=>$to_val]);
            }
        }

        if (isset($search_input_data->week_52_low) && $search_input_data->week_52_low) {
            $week_52_low_volume_value_exploded = explode('_',$search_input_data->week_52_low);
            $from_val = $week_52_low_volume_value_exploded[0];
            $to_val = $week_52_low_volume_value_exploded[1];
            if($from_val){
                $companies->where(['sd.low_price_52_week >= '=>$from_val]);
            }

            if($to_val){
                $companies->where(['sd.low_price_52_week <= '=>$to_val]);
            }
        }

        if (isset($search_input_data->index) && $search_input_data->index) {
            $companies->where(['Companies.exchange_id'=>$search_input_data->index]);
        }

        if (isset($search_input_data->sector) && $search_input_data->sector) {
            $companies->where(['Companies.sector'=>$search_input_data->sector]);
        }

        if (isset($search_input_data->industry) && $search_input_data->industry) {
            $companies->where(['Companies.industry'=>$search_input_data->industry]);
        }

        $detail = $companies;
        $count = $companies->count();
        $totalData = isset($count) ? $count : 0;

        $totalFiltered = $totalData;

        if (is_string($search_input_data)) {
            $detail = $detail
                ->where([
                    'OR' => [
                        ['(Companies.name) LIKE' => '%' . $search_input_data . '%'],
                        ['(Companies.symbol) LIKE' => '%' . $search_input_data . '%'],
                        ['(Companies.sector) LIKE' => '%' . $search_input_data . '%'],
                        ['(Exchanges.name) LIKE' => '%' . $search_input_data . '%'],
                        ['(Countries.market) LIKE' => '%' . $search_input_data . '%']
                    ]]);
        } else {
            if (isset($search_input_data->query) && $search_input_data->query) {
                $search = $search_input_data->query;
                $detail = $detail
                    ->where([
                        'OR' => [
                            ['(Companies.name) LIKE' => '%' . $search_input_data . '%'],
                            ['(Companies.symbol) LIKE' => '%' . $search_input_data . '%'],
                            ['(Companies.sector) LIKE' => '%' . $search_input_data . '%'],
                            ['(Exchanges.name) LIKE' => '%' . $search_input_data . '%'],
                            ['(Countries.market) LIKE' => '%' . $search_input_data . '%']
                        ]]);
                $company_count = $detail;
                $totalFiltered = $company_count->count();
            }            
        }


        $columns = array(
            0 => 'Companies.symbol',
            1 => 'Companies.name',
            6 => 'Exchanges.name',
            7 => 'Companies.sector',
        );

        $sidx = $columns[$requestData['order'][0]['column']];

        $sord = $requestData['order'][0]['dir'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $page = ($start) ? $start / $length : 1;

        $results = $detail
            ->order($sidx . ' ' . $sord)
            ->limit((int)$length)
            ->page($page);

        $i = 0;
        $data = array();
        $Stocks = TableRegistry::get('Stocks');

        foreach ($results as $row) {
            if ($language == self::JMD) {
                $stockInfo = $Stocks->getStockInformationLocal($row["symbol"], $row['id']);
            } else {
                $stockInfo = $Stocks->getStockInformation($row["symbol"]);
            }

            $price = $stockInfo['info']['1. open'] - $stockInfo['info']['4. close'];
            
            $change = 0;
            if ($stockInfo['info']['1. open'] > 0) {
                $change = (($price) * 100 / $stockInfo['info']['1. open']);
            }

            $class = "negative";
            if ($change >= 0) {
                $class = "positive";
            }

            $change = number_format($change, 2, '.', '');

            $nestedData = [];
            $nestedData[] = '<a class="' . $class . '" href="' . Router::url(['_name' => 'symbol', 'stock' => $row["symbol"]]) . '" ><span>' . $row["symbol"] . '</span></a>';
            $nestedData[] = $row["name"];
            $nestedData[] = '$' . number_format($stockInfo['info']['1. open'], 2, '.', ' ');
            $nestedData[] = '<span class="change-image ' . $class . '"></span><span class="' . $class . '"> ' . $change . '%</span>';
            $nestedData[] = '$' . number_format($price, 2);
            $nestedData[] = $stockInfo['info']['5. volume'];
            $nestedData[] = $row["exchange"]["name"];
            $nestedData[] = $row["sector"];
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
