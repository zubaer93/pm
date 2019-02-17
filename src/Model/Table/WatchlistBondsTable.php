<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Hash;

/**
 * WatchlistBonds Model
 *
 * @property \App\Model\Table\AppUsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\WatchlistBond get($primaryKey, $options = [])
 * @method \App\Model\Entity\WatchlistBond newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\WatchlistBond[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WatchlistBond|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\WatchlistBond patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\WatchlistBond[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\WatchlistBond findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WatchlistBondsTable extends Table
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

        $this->setTable('watchlist_bonds');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('AppUsers', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('WatchlistBondItems');
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

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
        $rules->add($rules->existsIn(['user_id'], 'AppUsers'));

        return $rules;
    }

    /**
     * getAllWatchlists method it will return all watchlists.
     *
     * @param string $userId User Id.
     * @return array
     */
    public function getAllWatchlists($userId)
    {
        $currentUser = $this->AppUsers->getUserAccountType($userId);

        $default = $this->find()
            ->where([
                'WatchlistBonds.user_id' => $userId,
                'WatchlistBonds.is_default' => true
            ])
            ->contain(['WatchlistBondItems'])
            ->toArray();

        $watchlists = [];
        if ($currentUser->account_type != 'FREE') {

            $limit = null;
            if ($currentUser->account_type == 'INDIVIDUAL') {
                $limit = 3;
            }

            $watchlists = $this->find()
                ->where([
                    'WatchlistBonds.user_id' => $userId,
                    'WatchlistBonds.is_default' => false
                ])
                ->contain(['WatchlistBondItems'])
                ->limit($limit)
                ->toArray();
        }
  
        $bonds = Hash::merge($default, $watchlists);

        $Bonds = TableRegistry::get('Bonds');
        $allBonds = $Bonds->getBondsBondeValue();

        foreach ($bonds as $bondKey => $userBond) {
            $items = [];
            foreach ($allBonds['items'] as $itemKey => $bond) {
                foreach ($userBond->watchlist_bond_items as $item) {
                    if ($item->isin_code == $bond['ISINCode']) {
                        $items[] = $bond;
                    }
                }

            }
            $bonds[$bondKey]['watchlist_bond_items'] = $items;
        }

        return $bonds;
    }

    /**
     * getList method it will return a list of watchlists.
     *
     * @param string $userId User Id.
     * @return array
     */
    public function getList($userId)
    {
        $currentUser = $this->AppUsers->getUserAccountType($userId);

        $defaultWatchlist = $this->find('list')
            ->where([
                'WatchlistBonds.user_id' => $userId,
                'WatchlistBonds.is_default' => true
            ])
            ->limit(1)
            ->toArray();

        $return = false;
        switch ($currentUser->account_type) {
            case 'FREE':
                $return = true;
                break;
            case 'INDIVIDUAL':
                $limit = 3;
                $return = false;
                break;
            case 'PROFESSIONAL':
                $limit = false;
                $return = false;
                break;
            case 'EXPERT':
                $limit = false;
                $return = false;
                break;
        }
        
        if ($return) {
            return $defaultWatchlist;
        }

        $watchlists = $this->find('list');
        $watchlists->where([
            'WatchlistBonds.user_id' => $userId,
            'WatchlistBonds.is_default' => false
        ]);

        if ($limit) {
            $watchlists->limit($limit);
        }

        $watchlists = $watchlists->toArray();

        return Hash::merge($defaultWatchlist, $watchlists);
    }

    /**
     * getAllWatchlists method it will return the default and the last watchlists created.
     *
     * @param string $userId User Id.
     * @return array
     */
    public function getWatchlists($userId)
    {
        $currentUser = $this->AppUsers->getUserAccountType($userId);

        $plans = $this->AppUsers->enum('account_type');

        $Bonds = TableRegistry::get('Bonds');
        $allBonds = $Bonds->getBondsBondeValue();

        $defaultWatchlist = $this->find()
            ->where([
                'WatchlistBonds.user_id' => $userId,
                'WatchlistBonds.is_default' => true
            ])->first();

        $defaultId = null;
        if ($defaultWatchlist) {
            $defaultId = $defaultWatchlist->id;
        }

        $lastWatchlist = false;
        if ($currentUser->account_type != 'FREE') {
            $lastWatchlist = $this->find()
                ->where([
                    'WatchlistBonds.user_id' => $userId,
                ])
                ->order(['WatchlistBonds.created' => 'DESC'])
                ->limit(1)
                ->first();
        }

        $lastId = null;
        if ($lastWatchlist) {
            $lastId = $lastWatchlist->id;
        }

        $bonds = $this->find()
            ->where(['WatchlistBonds.id IN' => [$defaultId, $lastId]])
            ->contain('WatchlistBondItems')
            ->toArray();

        foreach ($bonds as $bondKey => $userBond) {
            $items = [];
            foreach ($allBonds['items'] as $itemKey => $bond) {
                foreach ($userBond->watchlist_bond_items as $item) {
                    if ($item->isin_code == $bond['ISINCode']) {
                        $items[] = $bond;
                    }
                }

            }
            $bonds[$bondKey]['watchlist_bond_items'] = $items;
        }

        return $bonds;
    }

    /**
     * add method it will save a new watchlist group and validate if the user can create a new group.
     *
     * @param \Cake\ORM\Entity $watchlist
     * @return array
     */
    public function add($watchlist)
    {
        $currentUser = $this->AppUsers->getUserAccountType($watchlist->user_id);
        if ($currentUser->account_type == 'FREE') {
            return [
                'status' => false,
                'message' => 'You need to upgrade your plan to create new watchlists',
                'watchlist' => false,
                'item' => 'forex'
            ];
        }

        if ($currentUser->account_type == 'INDIVIDUAL') {
            $total = $this->find()
                ->where(['WatchlistBonds.user_id' => $watchlist->user_id])
                ->count();

            if ($total >= 4) {
                return [
                    'status' => false,
                    'message' => 'You need to upgrade your plan to create new watchlists',
                    'watchlist' => false,
                    'item' => 'forex'
                ];
            }
        }

        $status = false;
        $message = 'We could not save your watchlist group. Please try again';
        if ($this->save($watchlist)) {
            $status = true;
            $message = 'Watchlist group was saved.';
        }

        return [
            'status' => $status,
            'message' => $message,
            'watchlist' => $watchlist,
            'item' => 'forex'
        ];
    }
}
