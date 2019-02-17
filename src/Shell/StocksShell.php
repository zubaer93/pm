<?php

namespace App\Shell;

use Cake\Console\Shell;

class StocksShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Stocks');
    }

    public function updatePrices()
    {
        $this->Stocks->loadFromAlphaVantage();
        $this->out('Stock values have been updated');
    }

}