<?php

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transactions Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\AppUsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Transaction get($primaryKey, $options = [])
 * @method \App\Model\Entity\Transaction newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Transaction[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Transaction|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Transaction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Transaction[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Transaction findOrCreate($search, callable $callback = null, $options = [])
 */
class TransactionsTable extends Table
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

        $this->setTable('transactions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('BrokersList', [
            'className' => 'Brokers',
            'foreignKey' => 'broker',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
                ->numeric('price')
                ->allowEmpty('price');

        $validator
                ->numeric('fees')
                ->allowEmpty('fees');

        $validator
                ->numeric('limit_price')
                ->allowEmpty('limit_price');

        $validator
                ->numeric('total')
                ->allowEmpty('total');

        $validator
                ->allowEmpty('client_name');

        $validator
                ->integer('investment_amount')
                ->allowEmpty('investment_amount');

        $validator
                ->integer('investment_preferences')
                ->allowEmpty('investment_preferences');

        $validator
                ->allowEmpty('market');

        $validator
                ->integer('quantity_to_buy')
                ->allowEmpty('quantity_to_buy');

        $validator
                ->integer('status')
                ->allowEmpty('status');

        $validator
                ->integer('broker')
                ->allowEmpty('broker');

        $validator
                ->integer('action')
                ->allowEmpty('action');

        $validator
                ->integer('order_type')
                ->allowEmpty('order_type');

        $validator
                ->dateTime('created_at')
                ->requirePresence('created_at', 'create')
                ->allowEmpty('created_at');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * 
     * @param type $data
     * @param type $user_id
     * @return boolean
     */
    public function setTransactions($data, $user_id)
    {
        $result = true;
        if (isset($data)) {

            $transaction = $this->newEntity();
            $transaction->user_id = $user_id;
            $transaction->price = $data['company_price'];
            $transaction->fees = $data['total_fee'];
            $transaction->client_name = $data['client_name'];
            $transaction->investment_amount = $data['investment_amount'];
            $transaction->order_type = $data['order_type'];
            $transaction->market = $data['select_market'];
            $transaction->action = $data['action'];
            $transaction->investment_preferences = $data['investment_preferences'];
            $transaction->company_id = $data['company'];
            $transaction->quantity_to_buy = $data['quantity_to_buy'];
            $transaction->broker = $data['broker'];
            $transaction->total = $data['total'];
            $transaction->status = 0;
            if ($data['order_type'] === '2') {
                $transaction->limit_price = $data['limit_price'];
            }
            if (!$this->save($transaction)) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * 
     * @param type $id
     * @param type $data
     */
    public function updateTransaction($id, $data)
    {
        $limit = null;
        if ($data['order_type'] === '2') {
            $limit = $data['limit_price'];
        }
        $query = $this->query();
        $result = $query->update()
                ->set([
                    'client_name' => $data['client_name'],
                    'price' => $data['company_price'],
                    'fees' => $data['total_fee'],
                    'investment_amount' => $data['investment_amount'],
                    'order_type' => $data['order_type'],
                    'market' => $data['select_market'],
                    'action' => $data['action'],
                    'investment_preferences' => $data['investment_preferences'],
                    'company_id' => $data['company'],
                    'quantity_to_buy' => $data['quantity_to_buy'],
                    'broker' => $data['broker'],
                    'total' => $data['total'],
                    'limit_price' => $limit,
                    'status' => 0
                ])
                ->where(['id' => $id])
                ->execute();
        return (bool) $result;
    }

    public function cancelTransaction($id)
    {
        $query = $this->query();
        $query->update()
                ->set([
                    'status' => '4'
                ])
                ->where(['id' => $id])
                ->execute();
        return ['result' => true];
    }

    public function order($id)
    {
        $result = false;
        $order = $this->get($id); // Return order with id
        $order->status = '1';
        if ($this->save($order)) {
            $result = true;
        }

        return $result;
    }

    public function getTransactions($userId, $market, $subDomain = null)
    {
        $query = $this->find('all')
                ->contain([
                    'Companies' => function ($q) use ($market)
                    {
                        return $q->autoFields(false)
                                ->contain(['Exchanges' => function ($q) use ($market)
                                    {
                                        return $q->autoFields(false)
                                                ->contain(['Countries' => function ($q) use ($market)
                                                    {
                                                        return $q->autoFields(false)
                                                                ->where(['Countries.market' => $market]);
                                                    }]);
                                    }]);
                    }
                ])
                ->where(['Transactions.user_id' => $userId])
                ->where(['Transactions.status' => 0])
                ->orderDesc('Transactions.created_at');

        return $query;
    }

    /**
     * @param $id
     * @param $status
     * @return bool
     */
    public function adminOrderTransaction($id, $status)
    {
        $result = false;
        $order = $this->get($id); // Return order with id
        $order->status = $status;
        if ($this->save($order)) {
            $result = true;
        }
        return $result;
    }

}
