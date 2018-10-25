<?php

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\I18n\Number;

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
            $sector = file_get_contents('https://www.alphavantage.co/query?function=SECTOR&apikey=' . $this->apiKey);
            $jsonarray_min = json_decode($sector, true);
            if (isset($jsonarray_min['Rank A: Real-Time Performance'])) {
                return $jsonarray_min['Rank A: Real-Time Performance'];
            }
        } else {
            $data_stocks = TableRegistry::get('Api.Stocks');
            $data_companies = TableRegistry::get('Api.Companies');
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
                        if ($stockInfo['info']['open'] > 0) {
                            $precent += ($stockInfo['info']['open'] - $stockInfo['info']['close']) * 100 / $stockInfo['info']['open'];
                        }
                    }
                    $array_data[$val] = number_format($precent / count($sector), 2) . '%';
                }
            }
            return $array_data;
        }
        return [];
    }

    public function getSectorPerformances2($language, $sectorName)
    {
        if ($language == self::USD) {
            $sector = file_get_contents('https://www.alphavantage.co/query?function=SECTOR&apikey=' . $this->apiKey);
            $jsonarray_min = json_decode($sector, true);
            if (isset($jsonarray_min['Rank A: Real-Time Performance'])) {
                return $jsonarray_min['Rank A: Real-Time Performance'];
            }
        } else {
            $data_stocks = TableRegistry::get('Api.Stocks');
            $data_companies = TableRegistry::get('Api.Companies');
            $array_data = [];

            $sector = $data_companies->getCompanisBySectorAll($sectorName, $language);
            if (count($sector) > 0) {
                $precent = 0;
                foreach ($sector as $data) {
                    $stockInfo = $data_stocks->getStockInformationLocal($data['symbol'], $data['id']);
                    if ($stockInfo['info']['open'] > 0) {
                        $precent += ($stockInfo['info']['open'] - $stockInfo['info']['close']) * 100 / $stockInfo['info']['open'];
                    }
                }
                $array_data[$sectorName] = number_format($precent / count($sector), 2) . '%';
            }
            return $array_data;
        }
        return [];
    }

}
