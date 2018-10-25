<?php

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Behavior;
use Cake\Utility\Hash;
use Cake\ORM\Table;
use CakeDC\Users\Model\Table\UsersTable;
use Cake\Validation\Validator;
use Cake\Core\Configure;

class BrokersTable extends Table
{

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
                ->requirePresence('first_name', 'create')
                ->notEmpty('first_name', 'Please fill this field');

        $validator
                ->requirePresence('fee', 'create')
                ->notEmpty('fee', 'Please fill this field');
        $validator
                ->requirePresence('exchange_fee', 'create')
                ->notEmpty('exchange_fee', 'Please fill this field');
        $validator
                ->requirePresence('trade_fee', 'create')
                ->notEmpty('trade_fee', 'Please fill this field');

        $validator
                ->requirePresence('market', 'create')
                ->notEmpty('market', 'Please fill this field');
        return $validator;
    }

    /**
     * getAll method will return brokers.
     *
     * @return array
     */
    public function __getBrokersInfo($id, $market = 'USD')
    {
        return $this->find()
                        ->where(['id' => $id])
                        ->where(['market' => $market])
                        ->first();
    }

    public function disableBrokers($id)
    {
        $result = false;
        if (!is_null($id)) {
            $broker = $this->get($id);
            $broker->enable = 1;
            if ($this->save($broker)) {
                $result = true;
            }
        }
        return $result;
    }

    public function enableBrokers($id)
    {
        $result = false;
        if (!is_null($id)) {
            $broker = $this->get($id);
            $broker->enable = 0;
            if ($this->save($broker)) {
                $result = true;
            }
        }
        return $result;
    }

    public function deleteBroker($id)
    {
        $result = true;
        if (!is_null($id)) {
            $broker = $this->get($id);

            if (!$this->delete($broker)) {
                $result = false;
            }
        }
        return $result;
    }

    public function addBroker($data)
    {

        $result = true;
        if (!is_null($data)) {
            $broker = $this->newEntity();
            $broker->first_name = $data['first_name'];
            $broker->last_name = '';
            $broker->fee = $data['fee'];
            $broker->exchange_fee = $data['exchange_fee'];
            $broker->trade_fee = $data['trade_fee'];
            $broker->percent = ($data['market'] == self::JMD) ? 1 : 0;
            $broker->market = $data['market'];

            if (!$this->save($broker)) {
                $result = false;
            }
        }
        return $result;
    }

}
