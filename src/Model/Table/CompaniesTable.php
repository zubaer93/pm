<?php

namespace App\Model\Table;

use App\Model\Table\NewsTable;
use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;
use Search\Manager;
use Cake\Routing\Router;
use Cake\I18n\Time;

/**
 * Companies Model
 *
 * @property \App\Model\Table\ExchangesTable|\Cake\ORM\Association\BelongsTo $Exchanges
 *
 * @method \App\Model\Entity\Company get($primaryKey, $options = [])
 * @method \App\Model\Entity\Company newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Company[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Company|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Company patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Company[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Company findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CompaniesTable extends Table
{

    const JMD = 'JMD';
    const USD = 'USD';

    /**
     * Constants
     */
    const MINIMUM_LIMIT_COMPANIES = 6;
    const LIMIT_SECTOR = 20;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('companies');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Search.Search');
        $this->addBehavior('Muffin/Trash.Trash', [
            'events' => ['Model.beforeFind'],
            'field' => 'deleted'
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'photo' => []
        ]);

        $this->hasMany('Stocks', [
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Exchanges', [
            'foreignKey' => 'exchange_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('StocksDetails', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('KeyPeople', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER',
            'saveStrategy' => 'replace'
        ]);

        $this->belongsToMany('Affiliates');
    }

    public function afterSave($entity)
    {
        $searchSummaryObj = TableRegistry::get('SearchSummary');
        $entityData = $entity->data['entity'];
        $exchange_id = $entityData->exchange_id;

        if (!empty($exchange_id)) {
            $exchange = $this->Exchanges->get($exchange_id);
        }

        $data = [
            'name' => $entityData->name,
            'symbol' => $entityData->symbol,
            'exchange_id' => $entityData->exchange_id,
            'id' => $entityData->id,
            'exchange_name' => $exchange->name,
            'country_id' => $exchange->country_id
        ];
        $searchSummaryObj->singleCompany($data);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('symbol', 'create')
            ->notEmpty('symbol');

        $validator
            ->integer('ipoyear')
            ->requirePresence('ipoyear', 'create')
            ->notEmpty('ipoyear');

        $validator
            ->requirePresence('sector', 'create')
            ->notEmpty('sector');

        $validator->setProvider('upload', \Josegonzalez\Upload\Validation\DefaultValidation::class);

        $validator->add('photo', 'fileAboveMinHeight', [
            'rule' => ['isAboveMinHeight', 150],
            'message' => 'This image should at least be 180px high',
            'provider' => 'upload'
        ]);

        $validator->add('photo', 'fileAboveMinWidth', [
            'rule' => ['isAboveMinWidth', 250],
            'message' => 'This image should at least be 286px wide',
            'provider' => 'upload'
        ]);

        $validator->add('photo', 'fileSuccessfulWrite', [
            'rule' => 'isSuccessfulWrite',
            'message' => 'This upload failed',
            'provider' => 'upload'
        ]);

        $validator
            ->add('photo', 'file', [
                'rule' => [
                    'mimeType', [
                        'image/jpeg',
                        'image/png'
                    ]
                ],
                'message' => 'Just is allowed JPG or PNG'
            ])
            ->allowEmpty('photo');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['exchange_id'], 'Exchanges'));

        return $rules;
    }

    /**
     * search method this method will get the companies for the search box.
     *
     * @param string $query Value to be used in the search condition
     * @return void
     */
    public function search($query)
    {
        $limit = 5;
        $items = [];
        $itemsbyname = [];

        if (!isset($query['phrase']) && isset($query['q'])) {
            $query['phrase'] = $query['q'];
        }

        if (preg_match('/$/', $query['phrase'])) {
            $query['phrase'] = str_replace('$', '', $query['phrase']);
        }

        if (preg_match('/@/', $query['phrase'])) {
            $query['phrase'] = str_replace('@', '', $query['phrase']);
        }

        $symbols = $this->find()
            ->where([
                'OR' => [
                    'Companies.symbol' => $query['phrase'],
                    'Companies.name' => $query['phrase']
                ]
            ])
            ->contain(['Exchanges'])
            ->order(['Companies.symbol' => 'ASC']);

        foreach ($symbols as $key => $company) {
            $market = $this->getCompanyMarket($company->exchange->id);

            $items[$key] = [
                'type' => 'company',
                'name' => $company->name,
                'symbol' => $company->symbol,
                'exchange' => $company->exchange->name,
                'url' => Router::url(['_name' => 'symbol', 'stock' => $company->symbol, 'lang' => $market])
            ];
        }

        return $items;
    }

    /**
     * searchManager method
     *
     * @return \Search\Manager
     */
    public function searchManager()
    {
        $searchManager = $this->behaviors()->Search->searchManager();
        $searchManager->add('phrase', 'Search.Like', [
            'before' => true,
            'after' => true,
            'mode' => 'or',
            'comparison' => 'LIKE',
            'wildcardAny' => '*',
            'wildcardOne' => '?',
            'field' => ['name', 'symbol']
        ]);

        return $searchManager;
    }

    /**
     * getCompanyInfo method This method will return the company info
     *
     * @param string $symbol Symbol of a company
     * @return string
     */
    public function getCompanyInfo($symbol, $language)
    {
        $query = $this->find()
            ->contain(['Exchanges' => function ($q) use ($language) {
                return $q->autoFields(false)
                    ->contain(['Countries' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => $language]);
                    }]);
            }])
            ->contain(['KeyPeople', 'Affiliates'])
            ->where(['enable !=' => 1])
            ->where(['Companies.symbol' => $symbol])
            ->first();
        return $query;
    }

    /**
     * getAllCompanyWithLang method This method will return the company info
     *
     * @param string $symbol Symbol of a company
     * @return string
     */
    public function getAllCompanyWithLang($language)
    {
        $query = $this->find()
            ->contain(['Exchanges' => function ($q) use ($language) {
                return $q->autoFields(false)
                    ->contain(['Countries' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => $language]);
                        }]);
                    }])
            ->where(['enable !=' => 1])
            ->toList();
        return $query;
    }

    /**
     * getCompanisBySector method This method will return the company info
     *
     * @param string $symbol Symbol of a company
     * @return string
     */
    public function getCompanisBySector($sector, $language, $limit)
    {
        $query = $this->find()
            ->contain(['Exchanges' => function ($q) use ($language) {
                return $q->autoFields(false)
                ->contain(['Countries' => function ($q) use ($language) {
                    return $q->autoFields(false)
                        ->where(['Countries.market' => $language]);
                    }]);
                }])
            ->where(['Companies.enable !=' => 1])
            ->where(['Companies.sector' => $sector])
            ->where(['Companies.sector !=' => ''])
            ->order('rand()')
            ->limit($limit)
            ->toList();
        return $query;
    }

    /**
     * getCompanisBySector method This method will return the company info
     *
     * @param string $symbol Symbol of a company
     * @return string
     */
    public function getCompanisBySectorAll($sector, $language)
    {
        $query = $this->find()
            ->contain(['Exchanges' => function ($q) use ($language) {
                return $q->autoFields(false)
                    ->contain(['Countries' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => $language]);
                    }]);
            }])
            ->where(['Companies.enable !=' => 1])
            ->where(['Companies.sector !=' => ''])
            ->where(['Companies.sector' => $sector])
            ->toList();
        return $query;
    }

    /**
     * getCompaniesSector method
     *
     * @param string $language Current Language
     * @return array
     */
    public function getCompaniesSector($language)
    {
        $query = $this->find('all', [
                'fields' => [
                    'symbol' => 'Companies.symbol',
                    'id' => 'max(Companies.id)',
                    'sector' => 'max(Companies.sector)',
                ],
                'group' => 'Companies.sector'
            ])
            ->contain(['Exchanges' => function ($q) use ($language) {
                return $q->autoFields(false)
                    ->contain(['Countries' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => $language]);
                    }]);
            }])
            ->where(['Companies.enable !=' => 1])
            ->where(['Companies.sector !=' => ''])
            ->toList();

        return $query;
    }

    /**
     * getCompaniesByIndustry method This method will return the company info
     *
     * @param string $symbol Symbol of a company
     * @return string
     */
    public function getCompaniesByIndustry($industry, $language)
    {
        $query = $this->find()
            ->contain(['Exchanges' => function ($q) use ($language) {
                return $q->autoFields(false)
                    ->contain(['Countries' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => $language]);
                    }]);
                }])
            ->where(['Companies.enable !=' => 1])
            ->where(['Companies.industry' => $industry])
            ->where(['Companies.industry !=' => ''])
            ->order('rand()')
            ->limit(self::LIMIT_SECTOR)
            ->toList();
        return $query;
    }

    /**
     * getCompaniesInfo method This method will return the companies info
     *
     * @param string $language market of a company
     * @return string
     */
    public function getCompaniesInfo($language)
    {
        $exchanges = $this->getExchangeIds($language);
        $query = $this->find()
            ->where(['enable' => 0])
            ->where(['exchange_id IN' => $exchanges])
            ->toList();

        return $query;
    }

    public function getExchangeIds($language)
    {
        try {
            $country = $this->Exchanges->Countries->find('all')
                ->where(['market' => $language])
                ->first();
            $country = $country->id;
            $allCompanies = $this->Exchange->find('list', array('fields' => array('name', 'id')))
                ->where(['country_id' => $country])
                ->toArray();

            return array_flip($allCompanies);
        } catch (\Exception $e) {
        }
    }

    /**
     * getCompanyName method This method will return the company name
     *
     * @param integer $id Id of a company
     * @return string
     */
    public function getCompanyName($id)
    {
        $name = '';

        if (!is_null($id)) {
            try {
                $query = $this->get($id);
                $name = $query->name;
            } catch (\Exception $e) {
                $name = __('The company was deleted.');
            }
        }

        return $name;
    }

    /**
     * getCompanyId method This method will return the company id
     *
     * @param string $symbol symbol of a company and string $lang
     * @return string|integer
     */
    public function getCompanyId($symbol, $language)
    {
        $id = '';
        if (!is_null($symbol)) {
            try {
                $query = $this->find()
                    ->contain(['Exchanges' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->contain(['Countries' => function ($q) use ($language) {
                                return $q->autoFields(false)
                                    ->where(['Countries.market' => $language]);
                            }]);
                    }])
                    ->where(['Companies.enable !=' => 1])
                    ->where(['Companies.symbol' => $symbol])
                    ->first();
                if (!is_null($query)) {
                    $id = $query->id;
                }
            } catch (\Exception $e) {
                $id = __('The company was deleted.');
            }
        }

        return $id;
    }

    /**
     * getCompanySymbol method This method will return the company symbol
     *
     * @param integer $id Id of a company
     * @return string
     */
    public function getCompanySymbol($id)
    {
        $symbol = '';

        if (!is_null($id)) {
            try {
                $query = $this->get($id);
                $symbol = $query->symbol;
            } catch (\Exception $e) {
                $symbol = __('The company was deleted.');
            }
        }

        return $symbol;
    }

    /**
     * getCompanyMarket method This method will return the company market
     *
     * @param integer $id Id of a company
     * @return string
     */
    public function getCompanyMarket($id)
    {
        $name = '';

        if (!is_null($id)) {
            try {
                $market = $this->Exchanges->find()
                    ->contain(['Countries' => function ($q) {
                        return $q->autoFields(false);
                    }])
                    ->where(['Exchanges.id' => $id])
                    ->first();
                return $market->country->market;
            } catch (\Exception $e) {
                $name = __('The company was deleted.');
            }
        }

        return $name;
    }

    /**
     * getCompanyTickerInfo method This method will return the company info for ticker
     *
     * @param string $symbol Symbol of a company
     * @return string
     */
    public function getCompanyTickerInfo($symbol)
    {
        $stockInfo = $this->Stocks->find('all')
            ->where(['Stocks.symbol' => $symbol])
            ->order(['Stocks.last_refreshed' => 'desc'])
            ->contain(['Companies' => function ($q) {
                return $q->autoFields(false)
                    ->select(['Companies.id', 'Companies.symbol', 'Companies.name'])
                    ->where(['Companies.symbol !=' => '']);
            }])
            ->limit(1)
            ->first();

        return $stockInfo;
    }

    /**
     * getTrendingCompanies method this method will return 6 companies for navbar element.
     *
     * @return array
     */
    public function getTrendingCompanies($language)
    {
        $companies = $this->find()
            ->contain(['Exchanges' => function ($q) use ($language) {
                return $q->autoFields(false)
                    ->contain(['Countries' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => $language]);
                    }]);
                }])
            ->order('rand()')
            ->where(['enable !=' => 1])
            ->group(['Companies.id'])
            ->limit(self::MINIMUM_LIMIT_COMPANIES)
            ->toArray();

        foreach ($companies as $key => $company) {
            $info = $this->getStocksInfo([$company->symbol], $language);
            $companies[$key] = $info[0];
        }

        return $companies;
    }

    /**
     * getCompanyTickerInfo method This method will return the company info for ticker
     *
     * @param string $symbol Symbol of a company
     * @return string
     */
    public function getStocksInfo($symbols, $language)
    {
        if ($language == self::USD) {
            foreach ($symbols as $symbol) {
                $companyInfo = $this->getCompanyInfo($symbol, $language);
                $array = $this->Stocks->getStockInformation($companyInfo['symbol']);

                $stocksInfo[] = [
                    'open' => $array['info']['1. open'],
                    'close' => $array['info']['4. close'],
                    'symbol' => $symbol,
                    'volume' => $array['info']['5. volume'],
                    'last_refreshed' => $array['last_refreshed'],
                    'company' => [
                        'name' => $companyInfo['name']
                    ]
                ];
            }
        } else {
            foreach ($symbols as $symbol) {
                $companyInfo = $this->getCompanyInfo($symbol, $language);
                $array = $this->Stocks->getStockInformationLocal($symbol, $companyInfo['id']);
                $stocksInfo[] = [
                    'open' => $array['info']['1. open'],
                    'close' => $array['info']['4. close'],
                    'symbol' => $symbol,
                    'volume' => $array['info']['5. volume'],
                    'last_refreshed' => $array['last_refreshed'],
                    'company' => [
                        'name' => $companyInfo['name']
                    ]
                ];
            }
        }
        return $stocksInfo;
    }

    /**
     * addCompanyWatchList method This method save the stock in stocks table if doesn't exists
     *
     * @param string $symbol Symbol of a company
     * @return string
     */
    public function addCompanyWatchList($company_id, $Language = null)
    {
        $stock = $this->Stocks->find()->where(['Stocks.company_id' => $company_id])->first();

        if (!$stock) {
            if ($Language != self::JMD) {
                $this->Stocks->addStockFromAlphaVantage($company_id);
            }
        }

        return true;
    }

    /**
     * Get companies to mention.js
     *
     * @param string $market
     * @return Query
     */
    public function getMentionSymbols($market)
    {
        $companies = $this->find('all')
            ->select(['Companies.symbol', 'Companies.name'])
            ->contain(['Exchanges' => function ($q) use ($market) {
            return $q->autoFields(false)
                ->contain(['Countries' => function ($q) use ($market) {
                    return $q->autoFields(false)
                        ->where(['Countries.market' => $market]);
                    }]);
                }])
            ->order('rand()')
            ->limit(3500);
        return $companies;
    }

    public function importCsv($content, $fields = array(), $options = array())
    {
        $file = fopen($content, 'r');

        if ($file) {
            $data = [];
            if (empty($fields)) {
                $fields = fgetcsv($file, $options['length'], $options['delimiter'], $options['enclosure']);
                foreach ($fields as $key => $field) {
                    $field = trim($field);
                    if (empty($field)) {
                        continue;
                    }
                    $fields[$key] = strtolower($field);
                }
            } elseif ($options['headers']) {
                fgetcsv($file, $options['length'], $options['delimiter'], $options['enclosure']);
            }
            $r = 0;
            $alias = $this->alias();
            while ($row = fgetcsv($file, $options['length'], $options['delimiter'], $options['enclosure'])) {
                foreach ($fields as $f => $field) {
                    if (!isset($row[$f])) {
                        $row[$f] = null;
                    }
                    $row[$f] = trim($row[$f]);
                    if (strpos($field, '.')) {
                        $keys = explode('.', $field);
                        if ($keys[0] == $alias) {
                            $field = $keys[1];
                        }
                        if (!isset($data[$r])) {
                            $data[$r] = [];
                        }
                        $data[$r] = Hash::insert($data[$r], $field, $row[$f]);
                    } else {
                        $data[$r][$field] = $row[$f];
                    }
                }
                $r++;
            }
            fclose($file);
            return $data;
        } else {
            return false;
        }
    }

    public function importJamaicanCsv($content, $fields = array(), $options = array())
    {
        $file = fopen($content, 'r');

        if (!$file) {
            return false;
        }

        $data = [];
        if (empty($fields)) {
            $fields = fgetcsv($file, $options['length'], $options['delimiter'], $options['enclosure']);
            foreach ($fields as $key => $field) {
                $field = trim($field);
                if (empty($field)) {
                    continue;
                }
                $fields[$key] = strtolower($field);
            }
        } elseif ($options['headers']) {
            fgetcsv($file, $options['length'], $options['delimiter'], $options['enclosure']);
        }

        $r = 0;
        $alias = $this->alias();
        while ($row = fgetcsv($file, $options['length'], $options['delimiter'], $options['enclosure'])) {

            foreach ($fields as $f => $field) {

                if (!isset($row[$f])) {
                    $row[$f] = null;
                }
                $row[$f] = trim($row[$f]);

                if ($f == 2) {
                    $row[$f] = 0;
                } elseif ($f == 5) {
                    $row[$f] = '';
                } elseif ($f == 4) {
                    if ($row[$f] == 'Junior Market') {
                        $row[$f] = 4;
                    } elseif ($row[$f] == 'Main Market') {
                        $row[$f] = 5;
                    } elseif ($row[$f] == 'USD Market') {
                        $row[$f] = 6;
                    } elseif ($row[$f] == 'Bond Market') {
                        $row[$f] = 7;
                    }
                }

                if (strpos($field, '.')) {
                    $keys = explode('.', $field);
                    if ($keys[0] == $alias) {
                        $field = $keys[1];
                    }
                    if (!isset($data[$r])) {
                        $data[$r] = [];
                    }
                    $data[$r] = Hash::insert($data[$r], $field, $row[$f]);
                } else {
                    $data[$r][$field] = $row[$f];
                }
            }
            $r++;
        }

        fclose($file);

        return $data;
    }

    public function exportCsv($filename, $data, $options = array())
    {
        if ($file = fopen($filename, 'w')) {
            $firstRecord = true;
            foreach ($data as $record) {
                $record = $record->toArray();
                $row = array();
                foreach ($record as $field => $value) {
                    if (!is_array($value)) {
                        $row[] = $value;
                        if ($firstRecord) {
                            $headers[] = $field;
                        }
                        continue;
                    }
                    $table = $field;
                    $fields = $value;
                    foreach ($fields as $field => $value) {
                        if (!is_array($value)) {
                            if ($firstRecord) {
                                $headers[] = $table . '.' . $field;
                            }
                            $row[] = $value;
                        }
                    }
                }
                $rows[] = $row;
                $firstRecord = false;
            }
            if ($options['headers']) {
                fputcsv($file, $headers, $options['delimiter'], $options['enclosure']);
            }
            $r = 0;
            foreach ($rows as $row) {
                fputcsv($file, $row, $options['delimiter'], $options['enclosure']);
                $r++;
            }
            fclose($file);
            return _('Companies export successfull');
        } else {
            return false;
        }
    }

    public function disableCompany($id)
    {
        $result = false;
        if (!is_null($id)) {
            $company = $this->get($id);
            $company->enable = 1;
            if ($this->save($company)) {
                $result = true;
            }
        }
        return $result;
    }

    public function enableCompany($id)
    {
        $result = false;
        if (!is_null($id)) {
            $company = $this->get($id);
            $company->enable = 0;
            if ($this->save($company)) {
                $result = true;
            }
        }
        return $result;
    }

    public function saveCsv($data = array())
    {
        foreach ($data as $key => $company) {
            if (!preg_match('/^[1-9][0-9]*$/', $company['ipoyear'])) {
                $company['ipoyear'] = 0;
            }
            $data[$key] = $company;
        }

        $entities = $this->newEntities($data);
        $result = $this->saveMany($entities);

        if ($result) {
            return _('Companies import successfull');
        }
    }

    public function saveOrUpdateCsvCompany($data = array())
    {
        $result = $this->getCompanyInfo(trim($data['symbol_code']), self::JMD);

        if (is_null($result)) {
            $data['div_curr'] = self::JMD;
            $company = $this->newEntity();
            $company->name = (isset($data['symbol_name'])) ? $data['symbol_name'] : '';
            $company->symbol = trim($data['symbol_code']);
            $company->ipoyear = 0;
            $company->sector = (isset($data['sector_name'])) ? $data['sector_name'] : '';
            $company->exchange_id = (int) $this->getExchangeId($data);
            if (!$this->save($company)) {
                $result = false;
            }
            return $company['id'];
        } else {
            if (isset($data['symbol_name'])) {
                $result->name = $data['symbol_name'];
            }
            $result->symbol = $data['symbol_code'];
            $result->ipoyear = 0;
            if (isset($data['sector_name'])) {
                $result->sector = $data['sector_name'];
            }

            if (!$this->save($result)) {
                $result = false;
            }
            return $result['id'];
        }
    }

    public function getExchangeId($data)
    {
        try {
            $countryId = $this->Exchanges->Countries->getId($data['div_curr']);
            $ID = $this->Exchanges->find()->where(['country_id' => $countryId['id']])
                ->first();
            return $ID['id'];
        } catch (\Exception $e) {
            
        }
    }

    public function getExchanges($data)
    {
        $countryId = $this->Exchanges->Countries->getId($data['div_curr']);
        $exchanges = $this->Exchanges
            ->find('all')
            ->where(['country_id' => $countryId['id']])
            ->toArray();
        $company = [];
        foreach ($exchanges as $exchange) {
            $company[] = $exchange['id'];
        }
        return $company;
    }

    /**
     * filterCompany method will filter
     *
     * @param array $query
     * @return object
     */
    public function filterCompany($query)
    {
        $query = trim($query);

        return $this->find('all')->where([
            'OR' => [
                ['id LIKE' => '%' . (int) $query . '%'],
                ['name LIKE' => '%' . $query . '%'],
                ['symbol LIKE' => '%' . $query . '%'],
                ['ipoyear LIKE' => '%' . $query . '%'],
                ['sector LIKE' => '%' . $query . '%'],
                ['industry LIKE' => '%' . $query . '%']
            ]
        ]);
    }

    public function getSearchCompanyWithLang($language, $search)
    {
        $query = $this->find('list')
            ->where([
                'OR' => [
                    ['Companies.name LIKE' => '%' . $search . '%'],
                    ['Companies.symbol LIKE' => '%' . $search . '%'],
                ]
            ])
            ->contain(['Exchanges' => function ($q) use ($language) {
                return $q->autoFields(false)
                    ->contain(['Countries' => function ($q) use ($language) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => $language]);
                    }]);
            }])
            ->where(['enable !=' => 1])
            ->toArray();
        return $query;
    }
}
