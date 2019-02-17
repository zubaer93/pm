<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class NewsController extends AppController
{
    /**
     * get News
     *
     */
    public function news()
    {
    }

    public function ajaxManageNewsFrontSearch()
    {
        
        $requestData = $this->request->getData();

        $obj = new \App\Model\DataTable\NewsDataTable();
        $result = $obj->ajaxManageNewsFrontSearch($requestData,$this->_getCurrentLanguage());

        echo $result;
        exit;
    }

    /**
     * get News
     *
     * @param $slug string
     */
    public function index($slug)
    {
        $data = $this->getNews($slug);
        $this->set(compact('page', 'data'));
    }

    /**
     * getNews method this method will return a news from slug.
     *
     * @param string $slug News Slug.
     * @return void
     */
    public function getNews($slug)
    {
        try {
            $this->loadModel('News');

            $news = $this->News->find()
                ->where(['slug' => $slug])
                ->first();

            if (!is_null($news)) {
                if (empty($news->body)) {
                    $this->redirect($news->url);
                }
                return $news;
            }

            $this->redirect(['_name' => 'home']);
        } catch (\Exception $e) {
            $this->redirect(['_name' => 'home']);
        }
    }

    /**
     * getAllNews method this method will return a news from market.
     *
     * @param string $slug News Slug.
     * @return void
     */
    public function getAllNews($market)
    {
        $this->loadModel('News');

        $news = $this->News->find()
            ->where(['market' => $market])
            ->order('publishedAt DESC');
        return $news;
    }

}
