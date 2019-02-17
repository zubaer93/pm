<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Utility\Hash;

/**
 * Countries Model
 *
 * @property \App\Model\Table\ExchangesTable|\Cake\ORM\Association\HasMany $Exchanges
 *
 * @method \App\Model\Entity\Country get($primaryKey, $options = [])
 * @method \App\Model\Entity\Country newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Country[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Country|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Country patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Country[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Country findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TraderTable extends Table
{

    private $apiKey;
    protected $date;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('trader');

        $this->apiKey = Configure::read('alphavantage_key');
        $this->date = (new Time(Time::now(), 'America/New_York'))->modify('midnight');
    }

    public function afterSave($entity)
    {
        $searchSummaryObj = TableRegistry::get('SearchSummary');
        $entityData = $entity->data['entity'];
        $data = [
            'from_currency_code' => $entityData->from_currency_code,
            'from_currency_name' => $entityData->from_currency_name,
            'to_currency_code' => $entityData->to_currency_code,
            'to_currency_name' => $entityData->to_currency_name,
            'exchange_rate' => $entityData->exchange_rate,
            'id' => $entityData->id
        ];
        $searchSummaryObj->singleTrade($data);
    }

    /**
     * search method this method will get the companies for the search box.
     *
     * @param string $query Value to be used in the search condition
     * @return void
     */
    public function search($query)
    {
        $limit = 8;
        $items = [];
        $itemsbyname = [];
        if (preg_match('/$/', $query['phrase'])) {
            $query['phrase'] = str_replace('$', '', $query['phrase']);
        }

        if (preg_match('/@/', $query['phrase'])) {
            $query['phrase'] = str_replace('@', '', $query['phrase']);
        }

        $querybycurrency = $this->find()
                ->where([
            'OR' => [
                ['(from_currency_code) LIKE' => '%' . $query['phrase'] . '%'],
                ['(from_currency_name) LIKE' => '%' . $query['phrase'] . '%'],
                ['(to_currency_code) LIKE' => '%' . $query['phrase'] . '%'],
                ['(to_currency_name) LIKE' => '%' . $query['phrase'] . '%'],
            ]
        ]);

        foreach ($querybycurrency as $key => $currency) {
            $trader = $this->getTrader([$currency]);
            $trader = reset($trader);
            $items[$key] = [
                'type' => 'trader',
                'from_currency_code' => $trader['from_currency_code'],
                'to_currency_code' => $trader['to_currency_code'],
                'exchange' => $trader['exchange_rate']
            ];
        }
        return $items;
    }

    public function getTrader($data = null)
    {
        if (empty($data)) {
            $data = $this->find('all');
        }

        $array = [];
        foreach ($data as $key => $val) {
            $rate = TableRegistry::get('Rate');
            $result = $rate->getLastExchangeRateData($val);
            if (!is_null($result)) {
                $array[$key]['id'] = $val['id'];
                $array[$key]['from_currency_code'] = $val['from_currency_code'];
                $array[$key]['from_currency_name'] = $val['from_currency_name'];
                $array[$key]['to_currency_code'] = $val['to_currency_code'];
                $array[$key]['to_currency_name'] = $val['to_currency_name'];
                $array[$key]['exchange_rate'] = $result['exchange_rate'];
                $array[$key]['last_refreshed'] = (new Time($result['last_refreshed'], 'America/New_York'))->format("Y-m-d H:i:s");
                $array[$key]['high'] = $result['high'];
                $array[$key]['low'] = $result['low'];
            }
        }
        return $array;
    }

    public function getTraderInfoAPI()
    {
        $data = $this->find('all');
        $array = [];
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $fx = file_get_contents('https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=' . $val['from_currency_code'] . '&to_currency=' . $val['to_currency_code'] . '&apikey=' . $this->apiKey);
                $fxData = json_decode($fx, true);
                if (isset($fxData['Realtime Currency Exchange Rate'])) {
                    $result = $fxData['Realtime Currency Exchange Rate'];
                    $array[$key]['from_currency_code'] = $result['1. From_Currency Code'];
                    $array[$key]['from_currency_name'] = $result['2. From_Currency Name'];
                    $array[$key]['to_currency_code'] = $result['3. To_Currency Code'];
                    $array[$key]['to_currency_name'] = $result['4. To_Currency Name'];
                    $array[$key]['exchange_rate'] = $result['5. Exchange Rate'];
                    $array[$key]['last_refreshed'] = $result['6. Last Refreshed'];
                }
            }
        }
        return $array;
    }

    public function getTraderForShell()
    {
        $data = $this->find('all');
        $array = [];
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $fx = file_get_contents('https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=' . $val['from_currency_code'] . '&to_currency=' . $val['to_currency_code'] . '&apikey=' . $this->apiKey);
                $fxData = json_decode($fx, true);
                if (isset($fxData['Realtime Currency Exchange Rate'])) {
                    $result = $fxData['Realtime Currency Exchange Rate'];
                    $array[$key]['trader_id'] = $val['id'];
                    $array[$key]['exchange_rate'] = $result['5. Exchange Rate'];
                    $array[$key]['last_refreshed'] = $result['6. Last Refreshed'];
                    $array[$key]['high'] = '';
                    $array[$key]['low'] = '';
                }
            }
        }
        return $array;
    }

    public function setTraders()
    {
        $data = $this->getTraderForShell();
        $rate = TableRegistry::get('Rate');
        foreach ($data as $key => $d) {
            $record = $rate->find()->where(['trader_id' => $d['trader_id']])->first();
            $query = $rate->query();
            if ($record->last_refreshed < $this->date) {
                $result = $query->update()
                        ->set([
                            'exchange_rate' => $d['exchange_rate'],
                            'last_refreshed' => $d['last_refreshed'],
                            'high' => $d['exchange_rate'],
                            'low' => $d['exchange_rate']
                        ])
                        ->where(['trader_id' => $d['trader_id']])
                        ->execute();
            } else {

                if ($d['exchange_rate'] > $record->high) {
                    $result = $query->update()
                            ->set([
                                'exchange_rate' => $d['exchange_rate'],
                                'last_refreshed' => $d['last_refreshed'],
                                'high' => $d['exchange_rate']
                            ])
                            ->where(['trader_id' => $d['trader_id']])
                            ->execute();
                } elseif ($d['exchange_rate'] < $record->low) {
                    $result = $query->update()
                            ->set([
                                'exchange_rate' => $d['exchange_rate'],
                                'last_refreshed' => $d['last_refreshed'],
                                'low' => $d['exchange_rate']
                            ])
                            ->where(['trader_id' => $d['trader_id']])
                            ->execute();
                } else {
                    $result = $query->update()
                            ->set([
                                'exchange_rate' => $d['exchange_rate'],
                                'last_refreshed' => $d['last_refreshed']
                            ])
                            ->where(['trader_id' => $d['trader_id']])
                            ->execute();
                }
            }
        }
    }

    public function __getTraderInfoFromCurrencyPortfolio($currency)
    {
        list ($from, $to) = $this->explodeCurrency($currency);
        
        /**
         * form start
         */
        $fx = file_get_contents('https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=' . $from . '&to_currency=' . $to . '&apikey=' . $this->apiKey);
        $fxData = json_decode($fx, true);

        if (isset($fxData['Realtime Currency Exchange Rate'])) {
            $array[] = Hash::get($fxData, '5. Exchange Rate');
        } else {
            $array[] = 0;
        }
        /**
         * form end
         */
        /**
         * to start
         */
        $fx = file_get_contents('https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=' . $to . '&to_currency=' . $from . '&apikey=' . $this->apiKey);
        $fxData = json_decode($fx, true);

        if (isset($fxData['Realtime Currency Exchange Rate'])) {
            $array[] = Hash::get($fxData, '5. Exchange Rate');
        } else {
            $array[] = 0;
        }
        /**
         * to end
         */
        return $array;
    }

    public function __getTraderInfoFromCurrency($currency)
    {
        list ($from, $to) = $this->explodeCurrency($currency);

        $query = $this->find()
                ->where(['from_currency_code' => $from])
                ->where(['to_currency_code' => $to])
                ->first();
        if (!is_null($query)) {
            $data = $this->getTrader([$query]);
            $data = reset($data);
        } else {
            $data = null;
        }
        return $data;
    }

    protected function explodeCurrency($currency)
    {
        $data = preg_replace('/-/', ' ', $currency);

        $explode = explode(' ', $data);
        if (count($explode) == 2) {
            $form = trim($explode[0]);
            $to = trim($explode[1]);
            return [$form, $to];
        }
        return ['', ''];
    }

}
