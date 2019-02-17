<?php

namespace App\Model\Scrapper;

use App\Model\Scrapper\Core;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Http\Client;
use Cake\Utility\Hash;

class Scrapper
{

    public static $company_id = '';

    /**
     * bloombergcom method
     *
     * @param string $url Url to use in the curl request
     * @return bool
     */

    public static function bloombergcom($url)
    {
         $check = Core::checkUrl($url);
        if ($check) {
            $file_contents = file_get_contents($url);
            preg_match_all('/<div class=\"body\-copy fence\-body\">(.*?)<div class=\"touts\">/s', $file_contents, $estimates);
            if (isset($estimates[1])) {
                if (isset($estimates[1][0])) {
                    $html = trim(preg_replace('/<figure[^>]*>.*?<\/figure>/i', '', $estimates[1][0]));
                    return substr($html, 0, -6);
                }
            }

            return false;
        }

        return false;
    }

    /**
     * 
     * @param type $url
     * @param type $company_id
     * @return boolean
     */
    public static function jamstockex($url, $company_id)
    {
        self::$company_id = $company_id;
        $file_contents = file_get_contents($url);
        preg_match_all('/<h1 class=\"entry-title h4\">(.*?)<\/h1>/s', $file_contents, $estimates);

        if (isset($estimates[1])) {

            foreach ($estimates[1] as $link) {
                preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $link, $result_href);
                preg_match_all("#<a.*?>([^<]+)</a>#", $link, $foo);

                if (!empty($result_href)) {
                    # Found a link.
                    self::getjamstockexfile($result_href['href'][0], trim($foo[1][0]));
                }
            }
        }

        return false;
    }

    /**
     * 
     * @param type $url
     * @param type $company_id
     * @return boolean
     */
    public static function jamStockexAuditedFinancial($url, $company_id)
    {
        self::$company_id = $company_id;
        $file_contents = file_get_contents($url);

        preg_match_all('/<h1 class=\"entry-title h4\">(.*?)<\/h1>/s', $file_contents, $estimates);

        if (isset($estimates[1])) {

            foreach ($estimates[1] as $link) {
                preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $link, $result_href);
                preg_match_all("#<a.*?>([^<]+)</a>#", $link, $foo);

                if (!empty($result_href)) {
                    self::getjamstockexfile($result_href['href'][0], trim($foo[1][0]));

                }
            }
        }

        return false;
    }

    public static function getjamstockexfile($url, $rel)
    {
        if (!self::checkDataFinancial($rel)) {

            $file_contents = file_get_contents($url);

            preg_match_all('/<div class=\"entry-content\">(.*?)<\/div>/s', $file_contents, $estimates);
            if (isset($estimates[1])) {

                foreach ($estimates[1] as $link) {
                    preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $link, $result_href);

                    if (!empty($result_href)) {
                        # Found a link.
                        if (!file_exists(Configure::read('Users.financial.path'))) {
                            mkdir(Configure::read('Users.financial.path'));
                        }
                        $array_file_name = [];
                        foreach ($result_href['href'] as $val) {

                            $path = $val;
                            $filename = pathinfo($path, PATHINFO_BASENAME);

                            $fp = fopen(Configure::read('Users.financial.path') . $filename, 'w+');
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $val);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                            curl_setopt($ch, CURLOPT_NOPROGRESS, false);
                            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15000);
                            curl_setopt($ch, CURLOPT_FILE, $fp);
                            curl_exec($ch);
                            curl_close($ch);
                            fclose($fp);
                            $array_file_name[] = $filename;
                        }
                        self::setDataFinancial($array_file_name, $rel);
                    }
                }
            }
        }
    }

    public static function checkDataFinancial($rel)
    {
        $FinancialStatementFiles = TableRegistry::get('FinancialStatement');
        $result = $FinancialStatementFiles->checkStatement($rel);
        return $result;
    }

    public static function setDataFinancial($filename, $rel)
    {
        $data['company_id'] = self::$company_id;
        $data['file_name'] = $filename;
        $data['title'] = $rel;
        $FinancialStatementFiles = TableRegistry::get('FinancialStatement');
        $FinancialStatementFiles->setStatement($data);
    }

    /**
     * checkDataEvent method
     *
     * @param type $rel
     * @return type
     */
    public static function checkDataEvent($name)
    {
        $event = TableRegistry::get('Events');
        $result = $event->checkEvent($name);
        return $result;
    }

    /**
     * getDetails method Event details
     *
     * @param type $array
     * @param type $company_id
     */
    public static function getDetails($array, $company_id)
    {
        foreach ($array as $data) {
            if (!self::checkDataEvent($data['name'])) {
                $file_contents = file_get_contents($data['url']);
                preg_match_all('/<div class=\"entry-content\">(.*?)<\/div>/s', $file_contents, $estimates);
                $data['company_id'] = $company_id;
                $data['description'] = $data['name'];

                $event = TableRegistry::get('Events');
                $result = $event->addEventScrapper($data);
            }
        }
    }

    /**
     * jmdCrawlerDataEvent method scrap Event
     *
     * @param strig $symbol
     * @param int $companyId
     * @return bool
     */
    public static function jmdCrawlerDataEvent($symbol, $companyId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.jamstockex.com/events/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "action=search_events_grouped&em_search=BPOW&geo=&near=&scope%5B0%5D=&scope%5B1%5D=&category=0&country=&region=&town=&near_distance=25&near_unit=mi");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $file_contents = curl_exec($ch);

        curl_close($ch);

        preg_match_all('/<table cellpadding=\"0\" cellspacing=\"0\" class=\"events-table\" >(.*?)<\/table>/s', $file_contents, $estimates);
        $array = [];
        $i = 0;

        foreach ($estimates as $link) {

            if (count($link) && $symbol == 'AGM') {
                self::sendEmail('event', $symbol);
            }

            foreach ($link as $val) {

                $extract_tr = "/<tr>(.*)<\\/tr>/isU";
                $extract_td = "/<td>(.*)<\\/td>/isU";

                preg_match_all($extract_tr, $val, $result_date);
                preg_match_all($extract_td, $result_date[0][1], $date);
                $result_date = explode('<br/>', $date[1][0]);
                $result_time = explode('-', $result_date[1]);

                $date = date("Y-m-d", strtotime($result_date[0]));

                $time = date("H:i:s", strtotime($result_time[0]));

                preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $val, $result_href);

                preg_match_all("#<a.*?>([^<]+)</a>#", $val, $foo);
                preg_match_all("#<i.*?>([^<]+)</i>#", $val, $address);

                $array[$i]['url'] = $result_href['href'][0];
                $array[$i]['name'] = trim($foo[1][0]);
                $array[$i]['address'] = trim($address[1][0]);
                $array[$i]['time'] = $time;
                $array[$i]['date'] = $date;

                $i++;
            }
        }

        if (count($array)) {
            self::getDetails($array, $companyId);
        }

        return false;
    }

    /**
     * usdCrawlerDataEvent method scrap Event
     *
     * @param strig $symbol
     * @param int $companyId
     * @return bool
     */
    public static function usdCrawlerDataEvent()
    {
        $self = (new self);
        $self->__saveCorporateData();
        $self->__saveDividends();
    }

    /**
     * __saveCorporateData method scrap Event
     *
     * @return void
     */
    public static function __saveCorporateData()
    {
        $self = (new self);
        $http = $self->__clientClass();
        $response = $http->get('https://api.iextrading.com/1.0/ref-data/daily-list/corporate-actions/');

        $results = json_decode($response->body(), true);
        foreach ($results as $event) {
            $data = [];
            $company = $self->__hasCompany(Hash::get($event, 'CurrentSymbolinINETSymbology'));
            if ($company) {
                $data['name'] = Hash::get($event, 'NotesforEachEntry');
                $data['description'] = Hash::get($event, 'NotesforEachEntry');
                $data['company_id'] = $company->id;
                $data['activity_type'] = Hash::get($event, 'IssueEvent');
                $data['date'] = Hash::get($event, 'EffectiveDate');
                $data['time'] = '';
                $data['address'] = '';
                $data['location'] = '';
                if (!self::checkDataEvent($data['name']) && $data['date'] >= date('Y-m-d')) {
                    $event = TableRegistry::get('Events');
                    $event->addEventScrapper($data);
                }
            }
        }
    }

    /**
     * __saveDividends method scrap Event
     *
     * @return bool
     */
    public static function __saveDividends()
    {
        $self = (new self);
        $http = $self->__clientClass();
        $response = $http->get('https://api.iextrading.com/1.0/ref-data/daily-list/dividends/');

        $results = json_decode($response->body(), true);
        foreach ($results as $event) {
            $data = [];
            $company = $self->__hasCompany(Hash::get($event, 'SymbolinINETSymbology'));
            if ($company) {
                $data['name'] = Hash::get($event, 'NotesforEachEntry');
                $data['description'] = Hash::get($event, 'NotesforEachEntry');
                $data['company_id'] = $company->id;
                $data['activity_type'] = Hash::get($event, 'EventType');
                $data['date'] = Hash::get($event, 'RecordDate');
                $data['time'] = '';
                $data['address'] = '';
                $data['location'] = '';
                if (!self::checkDataEvent($data['name']) && $data['date'] >= date('Y-m-d')) {
                    $event = TableRegistry::get('Events');
                    $event->addEventScrapper($data);
                }
            }
        }
    }

    /**
     * __hasCompany method it will check if the company exists.
     *
     * @param string $symbol Company Symbol
     * @return bool
     */
    private function __hasCompany($symbol)
    {
        $Companies = TableRegistry::get('Companies');
        return $Companies->find()
            ->where(['Companies.symbol' => $symbol])
            ->first();
    }

    /**
     * 
     * @param type $template
     * @param type $symbol
     */
    public static function sendEmail($template, $symbol)
    {
        $email = new Email();

        $email
            ->template($template)
            ->emailFormat('html')
            ->to('info@stockgitter.com')
            ->from('pennyintelligence@stockgitter.com')
            ->subject("Event $symbol")
            ->viewVars(['symbol' => $symbol])
            ->send();
    }

    /**
     * __clientClass method Returning a client instance
     *
     * @return Cake\Http\Client
     */
    private function __clientClass()
    {
        return new Client();
    }

}
