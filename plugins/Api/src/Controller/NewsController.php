<?php

namespace Api\Controller;

use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;

class NewsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');

    }

    /**
     * get all news according to language
     *
     */
    public function index()
    {
        $this->loadModel('Api.News');

        $limit = $this->request->getQuery('limit');
        $limit = $limit ? $limit : 10;
        $this->paginate = [
            'fields' => ['News.id', 'News.title', 'News.urlToImage', 'publishedAt', 'url', 'market', 'slug', 'author'],
            'limit' => $limit,
            'order' => ['News.created_at' => 'DESC']
        ];
        $news = $this->paginate($this->News->find()->where(['market' => $this->currentLanguage]))->toArray();
        if (!empty($news)) {
            $this->apiResponse['data'] = $this->News->formatImageUrl($news);
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['News'];
        } else {
            $this->apiResponse['data'] = [];
            $this->apiResponse['paginate'] = $this->Paginator->configShallow($this->apiResponse)->request->getParam('paging')['News'];
        }
    }

    /**
     * getNews method this method will return details of a news from slug.
     *
     * @param string $slug News Slug.
     */
    public function slugNews($slug)
    {
        $this->request->allowMethod('get');
        $this->loadModel('Api.News');
        if (!empty($slug)) {
            $news = $this->News->find()
                ->where(['slug' => $slug])->orWhere(['id' => $slug])
                ->first();
            if (!empty($news)) {
                if (substr($news['urlToImage'], 0, 7) == 'http://' || $result = substr($news['urlToImage'], 0, 7) == 'https:/') {
                    $news['in_house'] = false;
                } else {
                    $news['in_house'] = true;
                    $news['urlToImage'] = Router::url($news['urlToImage']);
                }
                $this->apiResponse['data'] = $news;
            } else {
                throw new NotFoundException();
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please provide slug.';
        }
    }

    /**
     * get a company news
     *
     * @param string company symbol
     */
    public function getCompanyNews($symbol)
    {
        $this->request->allowMethod('get');
        $limit = $this->request->getQuery('limit');
        $limit = $limit ? $limit : 10;
        $this->loadModel('Api.News');
        if (!empty($symbol)) {
            $company_new = $this->News->find()
                ->select(['News.id', 'News.title', 'News.urlToImage', 'publishedAt', 'url', 'market', 'slug', 'author'])
                ->where(['market' => $this->currentLanguage])
                ->where([
                    'OR' => [
                        ['BINARY (body) LIKE' => '%' . $symbol . '%'],
                        ['BINARY (title) LIKE' => '%' . $symbol . '%'],
                        ['BINARY (description) LIKE' => '%' . $symbol . '%']
                    ]
                ])
                ->order(['created_at DESC'])
                ->limit($limit)
                ->toArray();
            if (!empty($company_new)) {
                $this->apiResponse['data'] = $this->News->formatImageUrl($company_new);
            } else {
                $this->apiResponse['data'] = [];
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please provide company symbol.';
        }
    }

    /**
     * get a company news
     *
     * @param string company symbol
     */
    public function trending($currency)
    {
        $this->request->allowMethod('get');
        $this->add_model(array('Api.News', 'Api.Trader'));
        if (!empty($currency)) {
            $exchangeInfo = $this->Trader->__getTraderInfoFromCurrency($currency);
            $trending = $this->News->getTraderNews($exchangeInfo);
            if (!empty($trending)) {
                $this->apiResponse['data'] = $this->News->formatImageUrl($trending);
            } else {
                $this->apiResponse['data'] = [];
            }
        } else {
            $this->httpStatusCode = 403;
            $this->apiResponse['error'] = 'Please provide currency info.';
        }
    }

    public function random()
    {

    }
}
