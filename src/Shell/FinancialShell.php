<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use App\Model\Scrapper\Scrapper;
use App\Model\Scrapper\Core;

class FinancialShell extends Shell
{

    /**
     * Start the shell and interactive console.
     *
     * @return void
     */
    public function main()
    {
        try {
            $Companies = TableRegistry::get('Companies');
            foreach ($Companies->getAllCompanyWithLang('JMD') as $company_info) {
                $this->__loadAuditedFinancialData($company_info['symbol'], $company_info['id']);
            }
        } catch (\Exception $e) {
            
        }
    }

    /**
     * __loadNewsData method will get the news feed from the URL 
     *
     * @return array
     */
    private function __loadFinancialData($symbol, $company_id)
    {

        $i = 1;
        while (Core::checkUrl('https://www.jamstockex.com/page/' . $i . '/?tag=' . $symbol . '&category_name=quarterly-financial-statements')) {
            Scrapper::jamstockex('https://www.jamstockex.com/page/' . $i . '/?tag=' . $symbol . '&category_name=quarterly-financial-statements', $company_id);
            $i++;
        }
    }

    /**
     * __loadNewsData method will get the news feed from the URL 
     *
     * @return array
     */
    private function __loadAuditedFinancialData($symbol, $company_id)
    {

        $i = 1;
        while (Core::checkUrl('https://www.jamstockex.com/page/' . $i . '/?tag=' . $symbol . '&category_name=audited-financial-statements')) {
            Scrapper::jamStockexAuditedFinancial('https://www.jamstockex.com/page/' . $i . '/?tag=' . $symbol . '&category_name=audited-financial-statements', $company_id);
            $i++;
        }
    }

}
