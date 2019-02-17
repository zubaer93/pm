<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;
use Search\Manager;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\Cache\Cache;

/**
 * Stocks Model
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
class StocksTable extends Table
{

    /**
     * Stocks Info
     */
    private $__stocksInfo = [];
    private $apiKey;
    protected $date;

    /**
     * Constants
     */
    const MINIMUM_LIMIT_STOCKS = 6;
    const USD = 'USD';
    const JMD = 'JMD';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->addBehavior('Timestamp');
        $this->setTable('stocks');
        $this->belongsTo('Companies', [
            'joinType' => 'INNER'
        ]);
        $this->date = (new Time(Time::now(), 'America/New_York'))->modify('midnight');
    }

    /**
     * __loadFromAlphaVantage this method will return the values from the alphaVantage api
     *
     * @param array $company Company Object
     * @return array
     */
    public function loadFromAlphaVantage()
    {
        $usd = $this->Companies->find('all')
            ->select(['Companies.id', 'Companies.symbol'])
            ->contain(['Exchanges' => function ($q) {
                return $q->autoFields(false)
                    ->contain(['Countries' => function ($q) {
                        return $q->autoFields(false)
                            ->where(['Countries.market' => 'USD']);
                    }]);
            }]);

        if (!empty($usd)) {
            foreach ($usd as $company) {
                try {
                    $stocks = file_get_contents('https://www.alphavantage.co/query?interval=1min&function=TIME_SERIES_INTRADAY&symbol=' . $company['symbol'] . '&apikey=' . Configure::read('alphavantage_key'));
                    $stocksData = json_decode($stocks, true);
                    if (isset($stocksData['Time Series (1min)'])) {

                        foreach ($stocksData['Time Series (1min)'] as $key => $data) {
                            $stock = $this->find()
                                ->where(['company_id' => $company['id']])
                                ->first();
                            if (is_null($stock)) {
                                $stock = $this->newEntity();
                                $stock->company_id = $company['id'];
                                $stock->information = $stocksData['Meta Data']['1. Information'];
                                $stock->symbol = $stocksData['Meta Data']['2. Symbol'];
                                $stock->last_refreshed = $stocksData['Meta Data']['3. Last Refreshed'];
                                $stock->output_size = $stocksData['Meta Data']['5. Output Size'];
                                $stock->time_zone = $stocksData['Meta Data']['6. Time Zone'];

                                $stock->time_series_date_time = $key;
                                $stock->open = $data['1. open'];
                                $stock->high = $data['2. high'];
                                $stock->low = $data['3. low'];
                                $stock->close = $data['4. close'];
                                $stock->volume = $data['5. volume'];
                                $this->save($stock);
                            } else {
                                $stock->company_id = $company['id'];
                                $stock->information = $stocksData['Meta Data']['1. Information'];
                                $stock->symbol = $stocksData['Meta Data']['2. Symbol'];
                                $stock->last_refreshed = $stocksData['Meta Data']['3. Last Refreshed'];
                                $stock->output_size = $stocksData['Meta Data']['5. Output Size'];
                                $stock->time_zone = $stocksData['Meta Data']['6. Time Zone'];

                                $stock->time_series_date_time = $key;
                                $stock->open = $data['1. open'];
                                $stock->high = $data['2. high'];
                                $stock->low = $data['3. low'];
                                $stock->close = $data['4. close'];
                                $stock->volume = $data['5. volume'];
                                $this->save($stock);
                            }
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    
                }
            }
        }
    }

    /**
     * this method add the stock information from the alphaVantage api in the stock table
     *
     * @param string $symbol Company Symbol
     * @return array
     */
    public function addStockFromAlphaVantage($company_id)
    {

        $company = $this->Companies->find('all')
                ->where(['id' => $company_id])
                ->first();

        $stocks = file_get_contents('https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=' . $company['symbol'] . '&outputsize=compact&apikey=' . Configure::read('alphavantage_key'));
        $stocksData = json_decode($stocks, true);

        if (isset($stocksData['Time Series (Daily)'])) {
            foreach ($stocksData['Time Series (Daily)'] as $key => $data) {
                $stock = $this->newEntity();
                $stock->company_id = $company['id'];
                $stock->information = $stocksData['Meta Data']['1. Information'];
                $stock->symbol = $stocksData['Meta Data']['2. Symbol'];
                $stock->last_refreshed = $stocksData['Meta Data']['3. Last Refreshed'];
                $stock->output_size = $stocksData['Meta Data']['4. Output Size'];
                $stock->time_zone = $stocksData['Meta Data']['5. Time Zone'];
                $stock->time_series_date_time = $key;
                $stock->open = $data['1. open'];
                $stock->high = $data['2. high'];
                $stock->low = $data['3. low'];
                $stock->close = $data['4. close'];
                $stock->volume = $data['5. volume'];
                $this->save($stock);
                return true;
            }
        }

        return true;
    }

    /**
     * 
     * @param type $language
     */
    public function getStockTopPerformInformation($language = 'JMD')
    {
        $data = $this->find('all', [
                    'fields' => ['last_refreshed' => 'Stocks.last_refreshed',
                        'open' => 'Stocks.open',
                        'price_change' => 'Stocks.price_change',
                        'symbol' => 'Stocks.symbol',
                        'price_change' => 'Stocks.price_change',
                        'name' => 'Companies.name',
                    ]
                ])
                ->contain([
                    'Companies' => function ($q) use ($language)
                    {
                        return $q->autoFields(false)
                                ->contain(['Exchanges' => function ($q) use ($language)
                                    {
                                        return $q->autoFields(false)
                                                ->contain(['Countries' => function ($q) use ($language)
                                                    {
                                                        return $q->autoFields(false)
                                                                ->where(['Countries.market' => $language]);
                                                    }]);
                                    }]);
                    }])
                ->where(['last_refreshed >=' => $this->date])
                ->order(['price_change DESC']);

        $new_array = array();
        foreach ($data as $item) {
            if (!array_key_exists($item->symbol, $new_array)) {
                $new_array[$item->symbol] = $item;
            }
        }

        return $new_array;
    }

    /**
     * 
     * @param type $language
     */
    public function getStockWorstPerformInformation($language = 'JMD')
    {
        $data = $this->find('all', [
            'fields' => [
                'last_refreshed' => 'Stocks.last_refreshed',
                'open' => 'Stocks.open',
                'price_change' => 'Stocks.price_change',
                'symbol' => 'Stocks.symbol',
                'price_change' => 'Stocks.price_change',
                'name' => 'Companies.name',
            ]
        ])
        ->contain(['Companies' => function ($q) use ($language) {
            return $q->autoFields(false)
                ->contain(['Exchanges' => function ($q) use ($language) {
                    return $q->autoFields(false)
                        ->contain(['Countries' => function ($q) use ($language) {
                            return $q->autoFields(false)
                                ->where(['Countries.market' => $language]);
                        }]);
                }]);
        }])
        ->where(['last_refreshed >=' => $this->date])
        ->order(['price_change ASC']);

        $new_array = array();
        foreach ($data as $item) {
            if (!array_key_exists($item->symbol, $new_array)) {
                $new_array[$item->symbol] = $item;
            }
        }

        return $new_array;
    }

    /**
     * getStockInformation method 
     *
     * @param string $symbol Company Symbol
     * @return array|bool
     */
    public function getStockInformation($symbol = null)
    {
        try {
            $company_min = @file_get_contents('https://query1.finance.yahoo.com/v7/finance/options/' . $symbol);
            $jsonarray_min = json_decode($company_min, true);

            if (isset($jsonarray_min['optionChain']['result'][0]['quote'])) {
                return[
                    'last_refreshed' => (new Time('America/New_York'))->format("Y-m-d H:i:s"),
                    'info' => [
                        '1. open' => (isset($jsonarray_min['optionChain']['result'][0]['quote']['regularMarketPrice'])) ? $jsonarray_min['optionChain']['result'][0]['quote']['regularMarketPrice'] : 0,
                        '2. high' => (isset($jsonarray_min['optionChain']['result'][0]['quote']['regularMarketDayHigh'])) ? $jsonarray_min['optionChain']['result'][0]['quote']['regularMarketDayHigh'] : 0,
                        '3. low' => (isset($jsonarray_min['optionChain']['result'][0]['quote']['regularMarketDayLow'])) ? $jsonarray_min['optionChain']['result'][0]['quote']['regularMarketDayLow'] : 0,
                        '4. close' => (isset($jsonarray_min['optionChain']['result'][0]['quote']['regularMarketPreviousClose'])) ? $jsonarray_min['optionChain']['result'][0]['quote']['regularMarketPreviousClose'] : 0,
                        '5. volume' => (isset($jsonarray_min['optionChain']['result'][0]['quote']['regularMarketVolume'])) ? $jsonarray_min['optionChain']['result'][0]['quote']['regularMarketVolume'] : 0
                    ],
                    'date' => (new Time('America/New_York'))->format("Y-m-d H:i:s")
                ];
            }
        } catch (\Exception $e) {
            $company_min = file_get_contents('https://www.alphavantage.co/query?interval=1min&function=TIME_SERIES_INTRADAY&symbol=' . $symbol . '&apikey=' . Configure::read('alphavantage_key'));
            $jsonarray_min = json_decode($company_min, true);
            if (isset($jsonarray_min['Time Series (1min)'])) {
                foreach ($jsonarray_min['Time Series (1min)'] as $key => $data) {
                    return [
                        'last_refreshed' => $jsonarray_min['Meta Data']['3. Last Refreshed'],
                        'date' => $key,
                        'info' => $data
                    ];
                }
            }
        }
        return false;
    }

    /**
     * this method return the stock information from the db 
     *
     * @param string $symbol Company Symbol
     * @return array|bool
     */
    public function getStockInformationLocal($symbol = null, $company_id)
    {
        $data = $this->find()
            ->where(['symbol' => $symbol])
            ->where(['company_id' => $company_id])
            ->order(['Stocks.id DESC'])
            ->first();

        if (is_null($data)) {
            return false;
        }

        $array = [
            '1. open' => $data['open'],
            '2. high' => $data['high'],
            '3. low' => $data['low'],
            '4. close' => $data['open'] + (-1 * $data['price_change']),
            '5. volume' => $data['volume'],
            '6.price_change' => $data['price_change'],
            '7.eps' => $data['eps'],
        ];

        return [
            'last_refreshed' => (new Time($data->last_refreshed, 'America/New_York'))->format("Y-m-d H:i:s"),
            'date' => (new Time($data->time_series_date_time, 'America/New_York'))->format("Y-m-d"),
            'info' => $array
        ];
    }

    public function getStockInformationChart($symbol = null, $company_id)
    {
        $data = $this->find()
                ->where(['symbol' => $symbol])
                ->where(['company_id' => $company_id])
                ->order(['Stocks.last_refreshed ASC']);

        if (is_null($data)) {
            return false;
        }

        foreach ($data as $val) {
            $array[] = [
                '1. open' => $val['open'],
                '2. high' => $val['high'],
                '3. low' => $val['low'],
                '4. close' => $val['close'],
                '5. volume' => $val['volume'],
                'last_refreshed' => (new Time($val['last_refreshed'], 'America/New_York')),
                'id' => $val['id'],
            ];
        }

        return [
            'last_refreshed' => (new Time('America/New_York'))->format("Y-m-d H:i:s"),
            'date' => (new Time('America/New_York'))->format("Y-m-d H:i:s"),
            'info' => $array
        ];
    }

    /**
     * this method return the stock information from the db 
     *
     * @param string $symbol Company Symbol
     * @return array|bool
     */
    public function getStockOptions($symbol = null)
    {
        $company_daily = file_get_contents('https://query1.finance.yahoo.com/v7/finance/options/' . $symbol);

        $array[] = $jsonarray_daily = json_decode($company_daily, true);

        return [
            'date' => $array[0]['optionChain']['result'][0]['options'],
            'calls' => (isset($array[0]['optionChain']['result'][0]['options'][0]['calls'])) ?
            $array[0]['optionChain']['result'][0]['options'][0]['calls'] :
            null,
            'puts' => (isset($array[0]['optionChain']['result'][0]['options'][0]['puts'])) ?
            $array[0]['optionChain']['result'][0]['options'][0]['puts'] :
            null,
        ];
    }

    /**
     * 
     * @param type $symbol
     * @return type
     */
    public function getStockMarketDepth($symbol = null, $language, $company_id, $open_price, $eps)
    {
        $data = [];
        if ($language == self::JMD) {
            $stockDetails = TableRegistry::get('StocksDetails');
            $query = $stockDetails->find()
                ->where(['StocksDetails.company_id' => $company_id])
                ->order(['StocksDetails.id DESC'])
                ->first();

            if (!is_null($query)) {
                $data['last_trade_pice'] = $query['last_traded_price'];
                $data['price_bid'] = $query['bid_price'];
                $data['price_ask'] = $query['ask_price'];
                $data['bid_size'] = $query['bid_price'];
                $data['ask_size'] = $query['ask_price'];
                $data['high'] = $query['days_high_price'];
                $data['low'] = $query['days_low_price'];
                $data['fiftyTwoWeekLow'] = $query['low_price_52_week'];
                $data['fiftyTwoWeekHigh'] = $query['high_price_52_week'];
                $data['sharesOutstanding'] = $query['totalissuedshares'];
                $data['volume'] = $query['trade_value'];
                $data['close'] = $query['close_price'];
                $data['open'] = $open_price;
                $data['change'] = $query['close_net_change'];
                $data['marketCap'] = $query['market_cap'];
                $data['dividendDate'] = $query['dividend_amount'];
                $data['highPrice52Ind'] = $query['high_price_52_ind'];
                $data['lowPrice52Ind'] = $query['low_price_52_ind'];
                $data['closeNetChange'] = $query['close_net_change'];
                $data['closePercentChange'] = $query['close_percent_change'];
                $data['bidPprice'] = $query['bid_price'];
                $data['askPrice'] = $query['ask_price'];
                $data['numOfTrades'] = $query['num_of_trades'];
                $data['preDividendAmount'] = $query['pre_dividend_amount'];
                $data['eps'] = $eps;
            }
        }

        return $data;
    }

    public function addStock($data)
    {
        $result = true;
        if (!is_null($data)) {
            $stock = $this->newEntity();
            $stock->company_id = $data['company'];
            $stock->information = $data['information'];
            $stock->symbol = $data['symbol'];
            $stock->intervals = $data['intervals'];
            $stock->output_size = $data['output_size'];
            $stock->time_zone = (isset($data['time_zone'])) ? data['time_zone'] : '';
            $stock->time_series_date_time = $data['time_series_date_time'];
            $stock->open = (float) $data['open'];
            $stock->high = (float) $data['high'];
            $stock->low = (float) $data['low'];
            $stock->close = (float) $data['close'];
            $stock->volume = (int) $data['volume'];
            $stock->eps = (float) $data['EPS'];

            $stock->status = 1;
            if (!$this->save($stock)) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * this method add the stock information from admin in the stock table
     *
     * @param array $data ,integer $id
     * @return bool
     */
    public function editStock($data, $id)
    {
        $result = true;
        if (!is_null($data)) {

            $stock = $this->get($id);
            $stock->information = $data['information'];
            $stock->symbol = $data['symbol'];
            $stock->intervals = $data['intervals'];
            $stock->output_size = $data['output_size'];
//            $stock->time_zone = $data['time_zone'];
            $stock->time_series_date_time = $data['time_series_date_time'];
            $stock->open = (float) $data['open'];
            $stock->high = (float) $data['high'];
            $stock->low = (float) $data['low'];
            $stock->close = (float) $data['close'];
            $stock->volume = (int) $data['volume'];
            $stock->eps = (float) $data['EPS'];

            if (!$this->save($stock)) {
                $result = false;
            }
        }
        return $result;
    }

    public function deleteStock($id)
    {
        $result = true;
        if (!is_null($id)) {
            $stock = $this->get($id);
            if (!$this->delete($stock)) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * importCSV method this method will save all content from CSV
     *
     * @param array $data data to be imported.
     * @param int $id Company Id
     * @return bool
     */
    public function importCSV($data, $companyId)
    {
        $result = true;
        foreach ($data as $val) {
            $stock = $this->newEntity();
            $stock->company_id = $companyId;
            $stock->symbol = $val['symbol_code'];
            $stock->high = (float) $val['days_high_price'];
            $stock->low = (float) $val['days_low_price'];
            $stock->close = (float) $val['close_price'];
            if (!$this->save($stock)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * saveOrUpdateCsvOne method this method will save or update one content from CSV
     *
     * @param array $data data to be imported.
     * @param int $id Company Id
     * @return bool
     */
    public function saveOrUpdateCsvOne($val, $companyId)
    {
        $result = false;
        if (!is_null($val)) {
            $stock = $this->newEntity();
            $stock->company_id = $companyId;
            $stock->symbol = trim($val['symbol_code']);
            $stock->open = (float) $val['last_traded_price'];
            $stock->high = (float) $val['days_high_price'];
            $stock->low = (float) $val['days_low_price'];
            $stock->close = (float) $val['close_price'];
            $stock->volume = (float) $val['total_traded_volume'];
            $stock->quantity = (isset($val['last_traded_quantity']) ? (float) $val['last_traded_quantity'] : 0);
            $stock->price_change = (isset($val['price_change'])) ? (float) $val['price_change'] : null;
            $stock->percentage_change = (isset($val['percentage_change'])) ? (float) $val['percentage_change'] : null;
            $stock->time_series_date_time = (new Time($val['trade_date'], 'America/New_York'))->format("Y-m-d H:i:s");
            $stock->last_refreshed = (new Time(Time::now(), 'America/New_York'))->setTimezone('US/Eastern')->format("Y-m-d H:i:s");
            $stock->time_zone = 'US/Eastern';
            $stock->currency = $val['div_curr'];
            if ($this->save($stock)) {
                $result = true;
            }
        }
        return $result;
    }

    public function getStockSimulationChart($symbol, $company_id = null, $date = null, $quantity = null, $price = null)
    {
        $date = (new Time($date, 'America/New_York'))->setTimezone('US/Eastern')->format("Y-m-d H:i:s");

        $array = $this->find()
                ->select(["period" => "last_refreshed", "a" => "close*$quantity"])
                ->where(['last_refreshed >=' => $date])
                ->where(['symbol' => $symbol])
                ->where(['company_id' => $company_id]);

        return $array;
    }

    public function getStockSimulationUSDChart($symbol, $date, $price, $quantity)
    {
        $date = (new Time($date, 'America/New_York'))->setTimezone('US/Eastern')->format("Y-m-d");
        $company_min = file_get_contents('https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=' . $symbol . '&apikey=' . Configure::read('alphavantage_key'));
        $jsonarray_min = json_decode($company_min, true);
        foreach ($jsonarray_min['Time Series (Daily)'] as $key => $val) {

            if ($key >= $date) {
                $loss = (float) ($val['4. close'] - $price) * $quantity;
                $array[] = [
                    'period' => $key,
                    'a' => $loss,
                ];
            }
        }
        if (empty($array)) {
            return $array[] = [
                'period' => '',
                'a' => ''
            ];
        }
        return $array;
    }

    public function getSectorPerformances()
    {
        $sector = file_get_contents('https://www.alphavantage.co/query?function=SECTOR&apikey=' . Configure::read('alphavantage_key'));
        $jsonarray_min = json_decode($sector, true);
        if (isset($jsonarray_min['Rank A: Real-Time Performance'])) {
            return $jsonarray_min['Rank A: Real-Time Performance'];
        }
        return [];
    }

    /**
     * 
     * @param type $id
     */
    public function getAllStocksFromCompanyId($id)
    {
        return $this->find('all')
            ->where(['company_id' => $id]);
    }

    /**
     *
     *
     * @param type $id
     * @param type $language
     */
    public static function getStocksFromCompanyId($id, $language)
    {
        $Stocks = TableRegistry::get('Stocks');
        if (self::USD == $language) {
            $this->getStockInformation();
        }
    }

}
