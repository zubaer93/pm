<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * WatchlistForexItems Model
 *
 * @property \App\Model\Table\AppUsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\TradersTable|\Cake\ORM\Association\BelongsTo $Traders
 *
 * @method \App\Model\Entity\WatchlistForexItem get($primaryKey, $options = [])
 * @method \App\Model\Entity\WatchlistForexItem newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\WatchlistForexItem[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WatchlistForexItem|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\WatchlistForexItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\WatchlistForexItem[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\WatchlistForexItem findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WatchlistForexItemsTable extends Table
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

        $this->setTable('watchlist_forex_items');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('WatchlistForex', [
            'foreignKey' => 'watchlist_forex_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Trader', [
            'foreignKey' => 'trader_id',
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['trader_id'], 'WatchlistForex'));
        $rules->add($rules->existsIn(['watchlist_forex_id'], 'WatchlistForex'));

        return $rules;
    }
}
