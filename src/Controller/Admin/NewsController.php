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

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Shell\NewsShell;
use Cake\Core\Configure;

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
     * news method this method is like a index
     *
     * @return void
     */
    public function news()
    {
    }

    /**
     * runSchedulemanual method this method will add a news for USD Market.
     *
     * @return void
     */
    public function runSchedulemanual()
    {
        $news = new NewsShell();
        $result = $news->main();

        if ($result) {
            $this->Flash->success(__('News successfully added.'));
        } else {
            $this->Flash->error(__('News was not added.'));
        }
        $this->redirect(['_name' => 'add_news']);
    }

    /**
     * addNews method this method will add a news.
     *
     * @return void
     */
    public function add()
    {
        $news = $this->News->newEntity();
        if ($this->request->is('post')) {

            $this->request->data('publishedAt', date('Y-m-d H:i:s', strtotime($this->request->getData('publishedAt'))));
            $news = $this->News->patchEntity($news, $this->request->getData());

            $news->source_id = $this->request->getData('source_name');
            $news->urlToImage = $this->News->uploadFile($this->request->getData());
            $result = $this->News->save($news);

            if ($result) {
                $this->loadModel('AppUsers');
                $this->AppUsers->notify($this->request->getData('companies._ids'), 'News', $news);
                $this->Flash->success(__('News successfully added.'));
            } else {
                $this->Flash->error(__('News was not added.'));
            }
            $this->redirect(['_name' => 'news_list']);
        }
        $this->set(compact('news'));
    }

    /**
     * editNews method this method will edit a news.
     *
     * @param $slug string
     * @return void
     */
    public function edit($id)
    {
        $news = $this->News->get($id, [
            'contain' => [
                'Companies'
            ]
        ]);

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data('publishedAt', date('Y-m-d H:i:s', strtotime($this->request->getData('publishedAt'))));
            $oldNews = $news;
            $news = $this->News->patchEntity($news, $this->request->getData());

            $news->source_id = $this->request->getData('source_name');
            $news->urlToImage = $this->News->uploadFile($this->request->getData());
            $result = $this->News->save($news);

            if ($result) {
                $this->loadModel('AppUsers');
                $this->AppUsers->notify($this->request->getData('companies._ids'), 'News', $news);
                $this->Flash->success(__('News successfully edited.'));
            } else {
                $this->Flash->error(__('News was not edited.'));
            }

            $this->redirect(['_name' => 'news_list']);
        }

        $companies = $this->News->Companies->find('list')
            ->matching('News', function ($q) use ($id) {
                return $q->where(['News.id' => $id]);
            });

        $this->set(compact('news', 'companies'));
        $this->set('_serialize', ['news']);
    }

    /**
     * deleteNews method this method will delete a news.
     *
     * @param $id integer
     * @return void
     */
    public function delete($id)
    {
        $news = $this->News->get($id);

        if ($this->News->delete($news)) {
            $this->News->deleteImg($news->urlToImage);
            $this->Flash->success(__('News successfully deleted.'));
        } else {
            $this->Flash->error(__('News was not deleted.'));
        }

        $this->redirect(['_name' => 'news_list']);
    }

    public function ajaxManageNewsSearch()
    {
        $requestData = $this->request->getData();

        $obj = new \App\Model\DataTable\NewsDataTable();
        $result = $obj->ajaxManageNewsSearch($requestData);
        echo $result;
        exit;
    }

}
