<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use App\Model\Scrapper\ScrapperNews;

class NewsShell extends Shell
{

    /**
     * Start the shell and interactive console.
     *
     * @return void
     */
    public function main()
    {
        $data = $this->__loadNewsData();
        $bool = $this->__setData($data);
        $this->crawlerDataJamaica();
        $this->crawlerDataJamstockex();
        $this->crawlerDataJamaicaObserver();
    }

    public function crawlerDataJamaica()
    {
        ScrapperNews::crawlerDataJamaica($this->__urlJamaica());

    }

    public function crawlerDataJamstockex()
    {
        ScrapperNews::crawlerDataJamstockex($this->__urlJamstockex());

    }

    public function crawlerDataJamaicaObserver()
    {
        ScrapperNews::crawlerDataJamaicaObserver($this->__urlJamaicaobserver());

    }
    /**
     * __loadNewsData method will get the news feed from the URL
     *
     * @return array
     */
    private function __loadNewsData()
    {
        $redirectUrl = $this->__url();
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $redirectUrl,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ]);

        $resp = curl_exec($curl);
        curl_close($curl);

        return json_decode($resp);
    }

    /**
     * __setData method
     *
     * @param array $data news data
     * @return void
     */
    private function __setData($data)
    {
        $this->loadModel('News');

        $bool = $this->News->setNews($data);
        return $bool;
    }

    /**
     * __url method this method will return the url from the config
     *
     * @return string
     */
    private function __url()
    {
        return Configure::read('News.url.bloomberg');
    }

    /**
     * __urlJamaica method this method will return the url from the config
     *
     * @return mixed
     */
    private function __urlJamaica()
    {
        return Configure::read('News.url.jamaica.fullpath');
    }

    /**
     * __urlJamaicaobserver method this method will return the url from the config
     *
     * @return mixed
     */
    private function __urlJamaicaobserver()
    {
        return Configure::read('News.url.jamaicaobserver.fullpath');
    }

    /**
     * __urlJamstockex method this method will return the url from the config
     *
     * @return mixed
     */
    private function __urlJamstockex()
    {
        return Configure::read('News.url.jamstockex.fullpath');
    }

}
