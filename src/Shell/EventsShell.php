<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use App\Model\Scrapper\Scrapper;
use App\Model\Scrapper\Core;

class EventsShell extends Shell
{
    public function main()
    {
        $this->updateJmdEvent();
        $this->updateUsdEvent();
    }

    /**
     * updateUsdEvent method it will crawler the sites and take the events to JMD version.
     *
     * @return void
     */
    public function updateJmdEvent()
    {
        $Companies = TableRegistry::get('Companies');
        foreach ($Companies->getAllCompanyWithLang('JMD') as $companyInfo) {
            $this->__loadEventData('JMD', $companyInfo->symbol, $companyInfo->id);
        }

        $this->out('Event values for JMD have been updated');
    }

    /**
     * updateUsdEvent method it will crawler the sites and take the events to USD version.
     *
     * @return void
     */
    public function updateUsdEvent()
    {
        $this->__loadEventData('USD');

        $this->out('Event values for USD have been updated');
    }

    /**
     * __loadEventData method it will crawler the sites and take the events.
     *
     * @param string $lang Language to load the events for
     * @param string $symbol Company symbol
     * @param int $id Company id
     * @return void
     */
    private function __loadEventData($lang, $symbol = null, $companyId = null)
    {
        $method = strtolower($lang) . 'CrawlerDataEvent';
        Scrapper::{$method}($symbol, $companyId);
    }
}
