<?php

namespace App\Shell;

use Cake\Console\Shell;

class TickerShell extends Shell
{
    const JMD = 'JMD';

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Companies');
        $this->loadModel('Stocks');
        $this->loadModel('StocksDetails');

        $this->path = '/home/jmddata/ftp';
        $this->backuppath = '/home/jmddata/backup';
        $this->filepath = null;
        $path = $this->path . '/*.[cC][sS][vV]';

        $files = glob($path);

        if (count($files)) {
            $files = array_combine(array_map("filemtime", $files), $files);

            $tmpArray = array_values($files);

            $this->filepath = array_shift($tmpArray);
        }
        $this->options = [
            'length' => 0,
            'delimiter' => ',',
            'enclosure' => '"',
            'escape' => '\\',
            'headers' => true,
            'text' => false,
            'excel_bom' => false,
        ];
    }

    public function import()
    {
        if (!is_null($this->filepath)) {
            $data = $this->Companies->importCsv($this->filepath, null, $this->options);
            $pos = strpos($this->filepath, 'Daily');
            if ($pos === false) {
                $this->tickerImport($data);
            } else {
                $this->dailyImport($data);
            }
            
            $filename = str_replace($this->path, '', $this->filepath);
            rename($this->filepath, $this->backuppath . $filename);
        }
    }

    public function dailyImport($data)
    {
        foreach ($data as $val) {
            try {
                $id = $this->Companies->saveOrUpdateCsvCompany($val);

                $bool = $this->Stocks->saveOrUpdateCsvOne($val, $id);
                $bool = $this->StocksDetails->saveCsvOne($val, $id);
            } catch (\Exception $e) {
                
            }
        }
        return $bool;
    }

    public function tickerImport($data)
    {
        foreach ($data as $val) {
            try {
                $array['symbol_code'] = $val['symbolcode'];
                $array['total_traded_volume'] = (float) $val['totalvolumetraded'];
                $array['last_traded_quantity'] = (int) $val['lasttradedquantity'];
                $array['close_price'] = (float) $val['close'];
                $array['days_low_price'] = (float) $val['low'];
                $array['days_high_price'] = (float) $val['high'];
                $array['last_traded_price'] = (float) $val['lasttradedprice'];
                $array['price_change'] = (float) $val['pricechange'];
                $array['percentage_change'] = (float) $val['percentagechange'];
                $array['trade_date'] = $val['last update'];
                $array['div_curr'] = self::JMD;
                $id = $this->Companies->saveOrUpdateCsvCompany($array);
                $bool = $this->Stocks->saveOrUpdateCsvOne($array, $id);
            } catch (\Exception $e) {
                
            }
        }
    }

}
