<?php

namespace App\Model\Service;

use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class TranscactionChart
{

    static private $apiKey;

    const JMD = 'JMD';
    const USD = 'USD';

    public static function transactionChart($symbol, $compony_id, $date, $current_language)
    {
        if ($current_language == self::JMD) {
            $paramsChart = self::getStockSimulationChart($symbol, $compony_id, $date);
        } else {
            $paramsChart = self::getStockSimulationUSDChart($symbol, $date);
        }
        $min = isset($paramsChart['min']) ? $paramsChart['min'] : [0];
        unset($paramsChart['min']);
        usort($paramsChart, function($a, $b) {
            if ($a['a'] == $b['a']) {
                return 0;
            }

            return ($a['a'] < $b['a']) ? -1 : 1;
        });
        $paramsChart['min'] = $min;
        $paramsChart = json_encode($paramsChart);

        return $paramsChart;
    }

    private static function getStockSimulationChart($symbol, $company_id = null, $date = null)
    {
        $stockInfo = TableRegistry::get('Stocks');
        $date = (new Time($date, 'America/New_York'))->setTimezone('US/Eastern')->modify('-1 week')->format("Y-m-d H:i:s");

        $array = $stockInfo->find()
                ->select(['period' => 'last_refreshed', 'a' => 'close'])
                ->where(['last_refreshed >=' => $date])
                ->where(['symbol' => $symbol])
                ->where(['company_id' => $company_id])
                ->toArray();
        
        return $array;
    }

    private static function getStockSimulationUSDChart($symbol, $date)
    {
        self::$apiKey = Configure::read('alphavantage_key');
        $date = (new Time($date, 'America/New_York'))->setTimezone('US/Eastern')->modify('-1 week')->format("Y-m-d");
        $company_min = file_get_contents('https://www.alphavantage.co/query?function=TIME_SERIES_DAILY_ADJUSTED&&symbol=' . $symbol . '&apikey=' . self::$apiKey);

        $jsonarray_min = json_decode($company_min, true);

        $min = [];
        $array = [];
        foreach ($jsonarray_min['Time Series (Daily)'] as $key => $val) {
            if ($key >= $date) {
                $loss = $val['4. close'];
                $array[] = [
                    'a' =>  number_format($loss, 2, '.', ''),
                    'period' => $key
                ];

                $min[] = number_format($loss, 2, '.', '');
            }
        }

        if (empty($array)) {
            return $array[] = [
                'period' => '',
                'a' => '',
                'min' => $min
            ];
        }

        $array['min'] = $min;

        return $array;
    }

}
