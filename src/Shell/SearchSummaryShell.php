<?php

namespace App\Shell;

use Cake\Console\Shell;

class SearchSummaryShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('SearchSummary');

    }

    public function main()
    {
        
        $this->SearchSummary->user();
        $this->SearchSummary->trader();
        $this->SearchSummary->company();
    }

}
