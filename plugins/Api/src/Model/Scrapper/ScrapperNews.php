<?php

namespace Api\Model\Scrapper;

use Api\Model\Scrapper\Core;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;

class ScrapperNews
{

    public static function crawlerDataJamaica($url)
    {
        $check = Core::checkUrl($url);

        if ($check) {
            $file_contents = file_get_contents($url);

            preg_match_all('/<div class=\"block block\-system block\-main block\-system\-main odd block\-without\-title\" id=\"block\-system\-main\">(.*?)<div class=\"block block\-views block\-section\-front\-block\-1 block\-views\-section\-front\-block\-1 even block\-without\-title" id=\"block\-views\-section\-front\-block\-1\">/s', $file_contents, $estimates);

            if (isset($estimates[1])) {

                if (isset($estimates[1][0])) {

                    preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $estimates[1][0], $result_href);
                }
            }
            if (isset($result_href['href'])) {
                $data = array_unique($result_href['href']);
                $newsArray = [];

                $news = TableRegistry::get('Api.News');
                foreach ($data as $key => $value) {
                    $domain = parse_url($value);
                    $val = Configure::read('News.url.jamaica.domain') . $domain['path'];
                    $checkIfExist = $news->hasUrl($val);

                    if ($checkIfExist) {
                        $main_image_sqc = '';
                        $description = '';
                        $content = '';
                        $title = '';

                        $file_contents = @file_get_contents($val);
                        preg_match_all('/<div class=\"views\-field views\-field\-field\-op\-main\-image\-1\">(.*?)<\/div>/s', $file_contents, $main_image);

                        if (isset($main_image[1])) {
                            if (isset($main_image[1][0])) {
                                preg_match_all('/<img[^>]+src=([\'"])(?<src>.+?)\1[^>]*>/i', $main_image[1][0], $result_src_image);
                                if (isset($result_src_image['src'])) {
                                    if (isset($result_src_image['src'][0])) {
                                        $domain = parse_url($result_src_image['src'][0]);
                                        $main_image_sqc = Configure::read('News.url.jamaica.domain') . $domain['path'];
                                    }
                                }
                            }
                        }

                        preg_match_all('/<div class=\"views\-field views\-field\-field\-caption\">(.*?)<\/div>/s', $file_contents, $result_description);

                        if (isset($result_description[1])) {
                            if (isset($result_description[1][0])) {
                                $description = $result_description[1][0] . '</div>';
                            }
                        }

                        preg_match_all('/<h1 id=\"page\-title\" class=\"title\">(.*?)<\/h1>/s', $file_contents, $result_title);

                        if (isset($result_title[1])) {
                            if (isset($result_title[1][0])) {
                                $title = $result_title[1][0];
                            }
                        }

                        preg_match_all('/<div class=\"field field\-name\-body field\-type\-text\-with\-summary field\-label\-hidden\">(.*?)<\/div>/s', $file_contents, $result_content);

                        if (isset($result_content[1])) {
                            if (isset($result_content[1][0])) {
                                $content = $result_content[1][0] . '</div></div>';
                            }
                        }
                        $newsArray[] = (object)['description' => $description
                            , 'body' => $content
                            , 'urlToImage' => $main_image_sqc
                            , 'title' => $title
                            , 'url' => $val
                            , 'author' => $val
                            , 'publishedAt' => (new \Cake\I18n\Time(\Cake\I18n\Time::now(), 'America/New_York'))->setTimezone('US/Eastern')
                        ];

                    }

                }
                $data['articles'] = $newsArray;

                $news_data = $news->setNews((object)$data, 'JMD');

                return $news_data;
            }
            return false;
        }
    }

    public static function crawlerDataJamstockex($url)
    {
        $check = Core::checkUrl($url);

        if ($check) {
            $file_contents = file_get_contents($url);

            preg_match_all('/<h1 class=\"entry-title h4\">(.*?)<\/h1>/s', $file_contents, $estimates);

            if (isset($estimates[1])) {
                $newsArray = [];

                foreach ($estimates[1] as $link) {
                    preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $link, $result_href);
                    preg_match_all("#<a.*?>([^<]+)</a>#", $link, $foo);

                    if (isset($result_href['href'])) {
                        $data = $result_href['href'];
                        $news = TableRegistry::get('Api.News');

                        foreach ($data as $key => $value) {
                            $domain = parse_url($value);
                            $val = Configure::read('News.url.jamstockex.domain') . $domain['path'];
                            $checkIfExist = $news->hasUrl($val);

                            if ($checkIfExist) {

                                $content = '';
                                $title = '';

                                $file_contents = @file_get_contents($val);

                                preg_match_all('/<div class=\"entry-content\">(.*?)<\/div>/s', $file_contents, $result_content);

                                if (isset($result_content[1])) {
                                    if (isset($result_content[1][0])) {
                                        $content = $result_content[1][0];
                                    }
                                }


                                preg_match_all('/<h1 class=\"entry\-title h2\">(.*?)<\/h1>/s', $file_contents, $result_title);;

                                if (isset($result_title[1])) {
                                    if (isset($result_title[1][0])) {
                                        $title = $result_title[1][0];
                                    }
                                }

                                $newsArray[] = (object)['body' => $content
                                    , 'title' => $title
                                    , 'url' => $val
                                    , 'author' => $val
                                    , 'publishedAt' => (new \Cake\I18n\Time(\Cake\I18n\Time::now(), 'America/New_York'))->setTimezone('US/Eastern')
                                ];

                            }
                        }

                    }

                }
                $data['articles'] = $newsArray;
                $news_data = $news->setNews((object)$data, 'JMD');

                return $news_data;
            }
        }
        return false;
    }


    public static function crawlerDataJamaicaObserver($url)
    {
        $result = self::get_web_page($url);

        if ($result['http_code'] == 200) {
            $file_contents = $result['content'];
            preg_match_all('/<div id=\"articles\_container\">(.*?)<div id=\"mosPagerResults\">/s', $file_contents, $estimates);

            if (isset($estimates[1])) {
                if (isset($estimates[1][0])) {
                    preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $estimates[1][0], $result_href);
                }
            }

            if (isset($result_href['href'])) {
                $data = array_unique($result_href['href']);
                $newsArray = [];

                $news = TableRegistry::get('Api.News');
                foreach ($data as $key => $value) {
                    $domain = parse_url($value);
                    $val = Configure::read('News.url.jamaicaobserver.domain') . $domain['path'];
                    $arr[] = $val;
                    $values = array_unique($arr);
                }

                foreach ($values as $path) {

                    $checkIfExist = $news->hasUrl($path);
                    if ($checkIfExist) {
                        $main_image_sqc = '';
                        $content = '';
                        $title = '';
                        $news_page_result = self::get_web_page($path);
                        if ($news_page_result['http_code'] == 200) {
                            $file_content = $news_page_result['content'];
                            preg_match_all('/<div id=\"story\">(.*?)<\/div>/s', $file_content, $result_content);

                            if (isset($result_content[1])) {
                                if (isset($result_content[1][1])) {
                                    $content = $result_content[1][1];
                                }
                            }

                            preg_match_all('/<div id=\"story\">(.*?)<div id=\"other\_stories\">/s', $file_content, $main_image);

                            if (isset($main_image[1])) {
                                if (isset($main_image[1][0])) {
                                    preg_match_all('/<img[^>]+src=([\'"])(?<src>.+?)\1[^>]*>/i', $main_image[1][0], $result_src_image);

                                    if (isset($result_src_image['src'])) {
                                        if (isset($result_src_image['src'][2])) {
                                            $domain = parse_url($result_src_image['src'][2]);
                                            $main_image_sqc = Configure::read('News.url.jamaicaobserver.domain') . $domain['path'];

                                        }
                                    }
                                }
                            }

                            preg_match_all('/<div id=\"story\">(.*?)<\/div>/s', $file_content, $result);


                            if (isset($result[0])) {
                                preg_match_all('/<h2>(.*?)<\/h2>/s', $result[0][0], $result_title);

                                if (isset($result_title[1][0])) {
                                    $title = $result_title[1][0];
                                }
                            }

                            $newsArray[] = (object)[
                                'body' => $content
                                , 'urlToImage' => $main_image_sqc
                                , 'title' => $title
                                , 'url' => $path
                                , 'author' => $path
                                , 'publishedAt' => (new \Cake\I18n\Time(\Cake\I18n\Time::now(), 'America/New_York'))->setTimezone('US/Eastern')
                            ];

                        }
                    }
                }
                
                $bata['articles'] = (object)$newsArray;
                $news_data = $news->setNews((object)$bata, 'JMD');
                return $news_data;
            }
            return false;
        }
    }

    private static function get_web_page($url)
    {
        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST => "GET",        //set request type post or get
            CURLOPT_POST => false,        //set to GET
            CURLOPT_USERAGENT => $user_agent, //set user agent
            CURLOPT_COOKIEFILE => "cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR => "cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING => "",       // handle all encodings
            CURLOPT_AUTOREFERER => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT => 120,      // timeout on response
            CURLOPT_MAXREDIRS => 10,       // stop after 10 redirects
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;
        return $header;
    }

}
