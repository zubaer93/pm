<?php
namespace App\Shell;

use Cake\Console\Shell;

class CompaniesShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Companies');
        $this->usa_filepath = $_SERVER['DOCUMENT_ROOT'] . 'webroot/files/companies.csv';
        $this->jam_filepath = $_SERVER['DOCUMENT_ROOT'] . 'webroot/files/jamaican_companies.csv';
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

    public function import($country = 'usa')
    {
//        if ($country == 'usa') {
//            $fields = [
//                'Companies.id',
//                'Companies.name',
//                'Companies.symbol',
//                'Companies.ipoyear',
//                'Companies.sector',
//                'Companies.industry',
//                'Companies.exchange_id'
//            ];
//
//            $data = $this->Companies->importCsv($this->usa_filepath, $fields, $this->options);
//
//        } elseif ($country == 'jam') {
//            $fields = [
//                'Companies.symbol',
//                'Companies.name',
//                'Companies.ipoyear',
//                'Companies.sector',
//                'Companies.exchange_id',
//                'Companies.industry',
//            ];
//
//            $data = $this->Companies->importJamaicanCsv($this->jam_filepath, $fields, $this->options);
//        }
//
//        $this->out(print_r($this->Companies->saveCsv($data), true));
    }

    public function export()
    {
    	$data = $this->Companies->find()->all();
    	$this->out(print_r($this->Companies->exportCsv($this->filepath, $data, $this->options)));
    }
}