<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;

class ForexShell extends Shell
{
    /**
     * Start the shell and interactive console.
     *
     * @return void
     */
    public function main()
    {
        $bool = $this->__setData();
        return $bool;
    }

    /**
     * __setData method
     *
     * @return void
     */
    private function __setData()
    {
        $this->loadModel('Trader');

        $bool = $this->Trader->setTraders();
        return $bool;
    }

    /**
     * deleteData method
     *
     * @return void
     */
    public function deleteData()
    {
        $this->loadModel('Rate');

        $bool = $this->Rate->deleteRate();

        return $bool;
    }

    /**
     * setRowRate method
     *
     * @return void
     */
    public function setRowRate()
    {
        $this->loadModel('Rate');

        $bool = $this->Rate->setRowRate();

        return $bool;
    }

}
