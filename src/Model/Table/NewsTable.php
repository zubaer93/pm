<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Scrapper\Scrapper;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;;
use Cake\Utility\Hash;

class NewsTable extends Table
{

    /**
     * CONST TO DEFINE STATIC VALUES
     */
    const USD = 'USD';
    const JMD = 'JMD';
    const NEWS_LIMIT = 6;
    const NEWS_LIMIT_USD = 6;
    const NEWS_LIMIT_JMD = 3;

    protected $newsLimit = 6;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('news');

        $this->belongsToMany('Companies', [
            'className' => 'Companies',
            'foreignKey' => 'news_id',
            'targetForeignKey' => 'company_id',
            'joinTable' => 'news_companies',
            'cascadeCallbacks' => true,
        ]);

        $this->addBehavior('Muffin/Slug.Slug', [
            'maxLength' => 255,
            'onUpdate' => true
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('source_name');

        $validator
            ->allowEmpty('author');

        $validator
            ->add('publishedAt', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('publishedAt');

        return $validator;
    }

    /**
     * getNews method will return news.
     *
     * @return array
     */
    public function getNews($market, $bool = true)
    {
        $limit = (self::USD == $market ) ? (self::NEWS_LIMIT_USD) : (self::NEWS_LIMIT_JMD);

        $array = $this->find()
            ->where(['market' => $market])
            ->order([
                'created_at DESC'
            ])
            ->limit($limit)
            ->toArray();

        if ($bool) {
            return $this->getRandom($array);
        }

        return $array;
    }

    /**
     * setNews method will save many articles
     *
     * @param array $data articles data
     * @param string $market
     * @return bool
     */

    public function setNews($data, $market = 'USD')
    {
        $result = true;
        if (!empty($data) && isset($data->articles)) {
            foreach ($data->articles as $val) {
                $checkUrlInDB = $this->hasUrl($val->url);
                if ($checkUrlInDB) {
                    if (!isset($val->body)) {
                        $body = $this->scrap($val->url);
                    } else {
                        $body = utf8_encode($val->body);
                    }
                    $news = $this->newEntity();
                    $news->source_id = (isset($val->source->id)) ? $val->source->id : 'Stockgitter';
                    $news->source_name = (isset($val->source->name)) ? $val->source->name : 'Stockgitter';
                    $news->title = $val->title;
                    $news->author = (isset($val->author) && !is_null($val->author)) ? $val->author : Configure::read('News.url.domain');
                    $news->body = $body;
                    $news->market = $market;
                    $news->description = '';
                    if (isset($val->description) && is_string($val->description)) {
                        $news->description = utf8_encode($val->description);
                    }
                    $news->url = $val->url;
                    $news->urlToImage = (isset($val->urlToImage)) ? $val->urlToImage : '/frontend_theme/img/_smarty/noimage.jpg';
                    $news->publishedAt = date('Y-m-d H:i:s', strtotime($val->publishedAt));
                    $news->companies = (isset($val->companies)) ? $val->companies : [];

                    if (!$this->save($news, ['associated' => ['Companies']])) {
                        $result = false;
                    }

                    if (!empty($news->companies)) {
                        $AppUsers = TableRegistry::get('AppUsers');
                        $AppUsers->notify(Hash::extract($news->companies, '{n}.id'), 'News', $news);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * scrap method
     *
     * @param string $url Url to be used in the curl requests
     * @return array
     */
    public function scrap($url)
    {
        try {
            $data = Scrapper::bloombergcom($url);
        } catch (\Exception $e) {
            $data = false;
        }

        return $data;
    }

    /**
     * getRandom method get Random of news
     *
     * @param array $news News data
     * @return array
     */
    public function getRandom($news)
    {
        if (count($news) >= 3) {

            $random = array_rand($news, 3);
            $arr = [];

            for ($i = 0; $i < count($random); $i++) {

                $arr[] = $news[$random[$i]];
            }
            return $arr;
        }

        return $news;
    }

    /**
     * getCompanyNews method this will return the news for a company
     *
     * @param string $symbol company simbol
     * @param string $language company market
     * @param string $companyName company name
     * @param int $limit News Limit
     * @return array
     */
    public function getCompanyNews($symbol, $language, $companyName = null, $limit = null)
    {
        if (!is_null($limit)) {
            $this->newsLimit = $limit;
        }

        $CompanyId = $this->Companies->getCompanyId($symbol, $language);
        $data = $this->find()
            ->matching('Companies', function ($q) use ($CompanyId) {
                return $q->where(['Companies.id' => $CompanyId]);
            });

        $newsId = [];
        foreach ($data as $news) {
            $newsId[] = $news->id;
        }

        $query = $this->find()
            ->where(['market' => $language])
            ->where([
                'OR' => [
                    ['BINARY (body) LIKE' => '%' . $symbol . '%'],
                    ['BINARY (title) LIKE' => '%' . $symbol . '%'],
                    ['BINARY (description) LIKE' => '%' . $symbol . '%'],
                    ['(body) LIKE' => '%' . $companyName . '%'],
                    ['(title) LIKE' => '%' . $companyName . '%'],
                    ['(description) LIKE' => '%' . $companyName . '%'],
                ]
            ])
            ->order(['created_at DESC']);

        if (!empty($newsId)) {
            $query = $query->orWhere(['id IN' => $newsId]);
        }

        $query = $query
            ->limit($this->newsLimit);

        return $query;
    }

    /**
     * getTraderNews method this will return the news for a trader
     *
     * @param string $symbol company simbol, $language company market,$company_name comapnay name
     * @return array
     */
    public function getTraderNews($exchangeInfo)
    {
        $query = $this->find()
            ->where([
                'OR' => [
                    ['BINARY (body) LIKE' => '%' . ' ' . $exchangeInfo['from_currency_code'] . ' %'],
                    ['BINARY (title) LIKE' => '%' . ' ' . $exchangeInfo['from_currency_code'] . ' %'],
                    ['BINARY (description) LIKE' => '%' . ' ' . $exchangeInfo['from_currency_code'] . ' %'],
                    ['BINARY (body) LIKE' => '%' . ' ' . $exchangeInfo['from_currency_name'] . ' %'],
                    ['BINARY (title) LIKE' => '%' . ' ' . $exchangeInfo['from_currency_name'] . ' %'],
                    ['BINARY (description) LIKE' => '%' . ' ' . $exchangeInfo['from_currency_name'] . ' %'],
                    ['BINARY (body) LIKE' => '%' . ' ' . $exchangeInfo['to_currency_code'] . ' %'],
                    ['BINARY (title) LIKE' => '%' . ' ' . $exchangeInfo['to_currency_code'] . ' %'],
                    ['BINARY (description) LIKE' => '%' . ' ' . $exchangeInfo['to_currency_code'] . ' %'],
                    ['BINARY (body) LIKE' => '%' . ' ' . $exchangeInfo['to_currency_name'] . ' %'],
                    ['BINARY (title) LIKE' => '%' . ' ' . $exchangeInfo['to_currency_name'] . ' %'],
                    ['BINARY (description) LIKE' => '%' . ' ' . $exchangeInfo['to_currency_name'] . ' %'],
                ]
            ])
            ->order(['created_at DESC']);

        $query = $query
            ->limit(self::NEWS_LIMIT)
            ->toArray();

        return $query;
    }

    /**
     * hasUrl method will check if we already have this url
     *
     * @param string $url Url to check if exists.
     * @return bool
     */
    public function hasUrl($url)
    {
        $data = $this->find()
            ->where(['url' => $url])
            ->first();

        if (empty($data)) {
            return true;
        }

        return false;
    }

    /**
     * uploadFile method
     *
     * @param $data array data from request
     * @return path
     */
    public function uploadFile($data)
    {
        $path = $data['image']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $filename = time() . '.' . $ext;
        $fullpath = Configure::read('News.image.fullpath') . $filename;

        if (!file_exists(Configure::read('News.image.fullpath'))) {
            mkdir(Configure::read('News.image.fullpath'), 0777, true);
        }
        if (!move_uploaded_file($data['image']['tmp_name'], $fullpath)) {
            return '';
        }

        return $filename;
    }

    /**
     * deleteImg method it will delete the current file.
     *
     * @param string $filename data from request
     * @return void
     */
    public function deleteImg($filename)
    {
        $filename = Configure::read('News.image.fullpath') . $filename;
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
}
