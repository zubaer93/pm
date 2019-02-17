<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * BuySellBroker Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\BrokersTable|\Cake\ORM\Association\BelongsTo $Brokers
 *
 * @method \App\Model\Entity\BuySellBroker get($primaryKey, $options = [])
 * @method \App\Model\Entity\BuySellBroker newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BuySellBroker[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BuySellBroker|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BuySellBroker patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BuySellBroker[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BuySellBroker findOrCreate($search, callable $callback = null, $options = [])
 */
class BuySellBrokerTable extends Table
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

        $this->setTable('buy_sell_broker');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id'
        ]);
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
                ->requirePresence('status', 'create')
                ->notEmpty('status');

//        $validator
//                ->dateTime('created_at')
//                ->requirePresence('created_at', 'create')
//                ->allowEmpty('created_at');

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
        $rules->add($rules->existsIn(['broker_id'], 'Brokers'));

        return $rules;
    }

    /**
     * 
     * @param type $id
     */
    public function deleteBuySellBroker($id)
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
     * @param type $company_id
     * @return boolean
     */
    public function deleteBuySellBrokersByCompanyId($company_id)
    {
        $result = true;
        if (!is_null($company_id)) {
            if (!$this->deleteAll([
                        'company_id' => $company_id
                    ])) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * 
     * @param type $id
     */
    public function editBuySellBroker($data)
    {

        $result = false;
        if (!is_null($data)) {
            if ($this->deleteBuySellBrokersByCompanyId($data['company_id'])) {
                $array['company_id'] = $data['company_id'];

                foreach ($data['broker_id'] as $key => $val) {
                    $array['broker_id'] = $val;
                    $array['created_at'] = $data['broker_created_at'][$key];
                    $array['status'] = $data['status'][$key];
                    $result = $this->addBuySellBroker($array);
                }
            }
        }
        return $result;
    }

    /**
     * 
     * @param type $id
     */
    public function addBuySellBroker($data)
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

    public static function getBuySellBroker($company_id, $broker_id)
    {
        $BuySellBroker = TableRegistry::get('BuySellBroker');

        $query = $BuySellBroker->find()
                ->where(['company_id' => $company_id])
                ->where(['broker_id' => $broker_id])
                ->first();

        if (is_null($query)) {
            $query['status'] = '';
        }
        return $query['status'];
    }

}
