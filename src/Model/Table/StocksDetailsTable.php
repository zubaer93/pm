<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StocksDetails Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\StocksDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\StocksDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StocksDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StocksDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StocksDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StocksDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StocksDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class StocksDetailsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('stocks_details');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
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
                ->requirePresence('high_price_52_week', 'create')
                ->allowEmpty('high_price_52_week');

        $validator
                ->requirePresence('high_price_52_ind', 'create')
                ->allowEmpty('high_price_52_ind');

        $validator
                ->requirePresence('low_price_52_week', 'create')
                ->allowEmpty('low_price_52_week');

        $validator
                ->requirePresence('low_price_52_ind', 'create')
                ->allowEmpty('low_price_52_ind');

        $validator
                ->requirePresence('days_high_price', 'create')
                ->allowEmpty('days_high_price');

        $validator
                ->requirePresence('days_low_price', 'create')
                ->allowEmpty('days_low_price');

        $validator
                ->requirePresence('close_price', 'create')
                ->allowEmpty('close_price');

        $validator
                ->requirePresence('close_net_change', 'create')
                ->allowEmpty('close_net_change');

        $validator
                ->requirePresence('close_percent_change', 'create')
                ->allowEmpty('close_percent_change');

        $validator
                ->requirePresence('last_traded_price', 'create')
                ->allowEmpty('last_traded_price');

        $validator
                ->requirePresence('bid_price', 'create')
                ->allowEmpty('bid_price');

        $validator
                ->requirePresence('ask_price', 'create')
                ->allowEmpty('ask_price');

        $validator
                ->requirePresence('total_traded_volume', 'create')
                ->allowEmpty('total_traded_volume');

        $validator
                ->requirePresence('trade_value', 'create')
                ->allowEmpty('trade_value');

        $validator
                ->requirePresence('num_of_trades', 'create')
                ->allowEmpty('num_of_trades');

        $validator
                ->requirePresence('market_cap', 'create')
                ->allowEmpty('market_cap');

        $validator
                ->requirePresence('totalissuedshares', 'create')
                ->allowEmpty('totalissuedshares');

        $validator
                ->requirePresence('pre_dividend_amount', 'create')
                ->allowEmpty('pre_dividend_amount');

        $validator
                ->requirePresence('pre_div_curr', 'create')
                ->allowEmpty('pre_div_curr');

        $validator
                ->requirePresence('dividend_amount', 'create')
                ->allowEmpty('dividend_amount');

        $validator
                ->requirePresence('div_curr', 'create')
                ->allowEmpty('div_curr');

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
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }

    /**
     * saveCsvOne method this method will save one content from CSV
     *
     * @param array $val data to be imported.
     * @param int $companyId Company Id
     * @return bool
     */
    public function saveCsvOne($val, $companyId)
    {
        $result = false;
        if (!is_null($val) && !is_null($companyId)) {
            try {
                $stock = $this->newEntity();
                $stock->company_id = $companyId;
                $stock->high_price_52_week = trim($val['high_price_52_week']);
                $stock->high_price_52_ind = trim($val['high_price_52_ind']);
                $stock->low_price_52_week = trim($val['low_price_52_week']);
                $stock->low_price_52_ind = trim($val['low_price_52_ind']);
                $stock->days_high_price = trim($val['days_high_price']);
                $stock->days_low_price = trim($val['days_low_price']);
                $stock->close_price = trim($val['close_price']);
                $stock->close_net_change = trim($val['close_net_change']);
                $stock->close_percent_change = trim($val['close_percent_change']);
                $stock->last_traded_price = trim($val['last_traded_price']);
                $stock->bid_price = trim($val['bid_price']);
                $stock->ask_price = trim($val['ask_price']);
                $stock->total_traded_volume = trim($val['total_traded_volume']);
                $stock->trade_value = trim($val['trade_value']);
                $stock->num_of_trades = trim($val['num_of_trades']);
                $stock->market_cap = trim($val['market_cap']);
                $stock->totalissuedshares = trim($val['totalissuedshares']);
                $stock->pre_dividend_amount = trim($val['pre_dividend_amount']);
                $stock->pre_div_curr = trim($val['pre_div_curr']);
                $stock->dividend_amount = trim($val['dividend_amount']);
                $stock->div_curr = trim($val['div_curr']);

                if ($this->save($stock)) {
                    $result = true;
                }
            } catch (\Exception $e) {
                var_dump($e);
            }
        }
        return $result;
    }

    /**
     * 
     * @param type $data
     */
    public function editStock($data)
    {
        $result = false;
        if (!is_null($data)) {
            $result = (bool) $this->query()
                            ->update()
                            ->set($data)
                            ->where(['id' => $data['id']])
                            ->execute();
        }
        return $result;
    }

    /**
     * 
     * @param type $id
     */
    public function deleteStocksDetails($id)
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
     * 
     * @param type $id
     */
    public function addStocksDetails($data)
    {
        $result = false;
        if (!is_null($data)) {
            $entities = $this->newEntity($data);
            if ($this->save($entities)) {
                $result = true;
            }
        }
        return $result;
    }

}
