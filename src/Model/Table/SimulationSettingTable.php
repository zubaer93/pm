<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SimulationSetting Model
 *
 * @property \App\Model\Table\BrokersTable|\Cake\ORM\Association\BelongsTo $Brokers
 *
 * @method \App\Model\Entity\SimulationSetting get($primaryKey, $options = [])
 * @method \App\Model\Entity\SimulationSetting newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SimulationSetting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SimulationSetting|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SimulationSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SimulationSetting[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SimulationSetting findOrCreate($search, callable $callback = null, $options = [])
 */
class SimulationSettingTable extends Table
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

        $this->setTable('simulation_setting');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Brokers', [
            'foreignKey' => 'broker_id'
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
                ->requirePresence('investment_amount', 'create')
                ->notEmpty('investment_amount');

        $validator
                ->requirePresence('quantity', 'create')
                ->notEmpty('quantity');
        $validator
                ->requirePresence('broker_id', 'create')
                ->notEmpty('broker_id');

        $validator
                ->allowEmpty('market');

        $validator
                ->dateTime('created_at')
                ->requirePresence('created_at', 'create')
                ->notEmpty('created_at');

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
        $rules->add($rules->existsIn(['broker_id'], 'Brokers'));

        return $rules;
    }

    public function setSimulation($data, $user_id)
    {
        $result = true;

        if (!empty($data)) {
            $simulation = $this->newEntity();
            $simulation->user_id = $user_id;
            $simulation->investment_amount = $data['investment_amount'];
            $simulation->quantity = $data['quantity'];
            $simulation->market = $data['market'];
            $simulation->broker_id = $data['broker_id'];
            if (!$this->save($simulation)) {
                $result = false;
            }
        }

        return $result;
    }

}
