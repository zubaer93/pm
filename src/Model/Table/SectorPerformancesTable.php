<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\I18n\Number;
use Cake\Http\Client;
use Cake\Utility\Hash;

/**
 * SectorPerformances Model
 *
 * @method \App\Model\Entity\SectorPerformance get($primaryKey, $options = [])
 * @method \App\Model\Entity\SectorPerformance newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SectorPerformance[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SectorPerformance|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SectorPerformance patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SectorPerformance[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SectorPerformance findOrCreate($search, callable $callback = null, $options = [])
 */
class SectorPerformancesTable extends Table
{

    private $apiKey;

    const USD = 'USD';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('sector_performances');
        $this->setPrimaryKey('id');
        $this->apiKey = Configure::read('alphavantage_key');
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
            ->allowEmpty('name');

        $validator
            ->allowEmpty('percent');

        return $validator;
    }

    /**
     * 
     * @param type $language
     * @return type
     */
    public function getSectorPerformances($language)
    {
        if ($language == self::USD) {
            $http = $this->__clientClass();

            $response = $http->get('https://api.iextrading.com/1.0/stock/market/sector-performance');
            $result = $response->body();
            $result = json_decode($result, true);
            if (is_array($result)) {
                $result = Hash::combine($result, '{n}.name', '{n}.performance');
                foreach ($result as $key => $value) {
                    $result[$key] = number_format(($value * 100), 3, '.', '') . '%';
                }

                return $result;
            }
            
        } else {
            $data_stocks = TableRegistry::get('Stocks');
            $data_companies = TableRegistry::get('Companies');
            $array_data = [];
            $array[] = 'COMMUNICATIONS';
            $array[] = 'CONGLOMERATES';
            $array[] = 'FINANCE';
            $array[] = 'INSURANCE';
            $array[] = 'MANUFACTURING';
            $array[] = 'OTHER';
            $array[] = 'PRIVATE';
            $array[] = 'RETAIL';
            $array[] = 'TOURISM';
            $array[] = 'TOURISM';

            foreach ($array as $val) {
                $sector = $data_companies->getCompanisBySectorAll($val, $language);

                if (count($sector) > 0) {
                    $precent = 0;
                    foreach ($sector as $data) {
                        $stockInfo = $data_stocks->getStockInformationLocal($data['symbol'], $data['id']);
                        if ($stockInfo['info']['1. open'] > 0) {
                            $precent += ($stockInfo['info']['1. open'] - $stockInfo['info']['4. close']) * 100 / $stockInfo['info']['1. open'];
                        }
                    }
                    $array_data[$val] = number_format($precent / count($sector), 2) . '%';
                }
            }
            return $array_data;
        }
        return [];
    }

    /**
     * __clientClass method Returning a client instance
     *
     * @return Cake\Http\Client
     */
    private function __clientClass()
    {
        return new Client();
    }

}
