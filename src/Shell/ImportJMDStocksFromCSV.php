<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;

/**
 * Description of ImportJMDStocksFromCSV
 *
 * @author zevs
 */

class SeaShell extends Shell
{
    // Found in src/Shell/Task/SoundTask.php
    public $tasks = ['Sound'];

    public function main()
    {
        $this->Sound->main();
    }
}


class ImportJMDStocksFromCSVShell extends Shell {

    public function main() 
    {
        $this->loadModel('Stocks');
        $this->loadModel('Companies');
        $path = '/home/jmddata';
        $cdir = scandir($path); 

        $options = [
            'length' => 0,
            'delimiter' => ',',
            'enclosure' => '"',
            'escape' => '\\',
            'headers' => true,
            'text' => false,
            'excel_bom' => false,
        ];

        $data = $this->Companies->importCsv($path, null, $options);

        $this->Stocks->importCSV($data, $id);

    }

}
