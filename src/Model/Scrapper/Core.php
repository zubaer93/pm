<?php

namespace App\Model\Scrapper;

class Core
{
    /**
     * checkUrl method 
     *
     * @param string $url Url to make a curl
     * @return bool
     */
    public static function checkUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10); //follow up to 10 redirections - avoids loops
        $data = curl_exec($ch);
        curl_close($ch);
        preg_match_all("/HTTP\/1\.[1|0]\s(\d{3})/", $data, $matches);

        $code = end($matches[1]);

        if ($data) {
            if ($code == 200) {
                return true;
            } elseif ($code == 404) {
                return false;
            }
        }

        return false;
    }
}
