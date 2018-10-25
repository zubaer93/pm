<?php

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Watchlist Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 *
 * @method \App\Model\Entity\Watchlist get($primaryKey, $options = [])
 * @method \App\Model\Entity\Watchlist newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Watchlist[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Watchlist|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Watchlist patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Watchlist[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Watchlist findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WatchlistGroupTable extends Table
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

        $this->setTable('watchlist_group');
        $this->belongsTo('AppUsers', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Watchlist', [
            'foreignKey' => 'group_id',
        ]);

    }

    /**
     *
     * @param type $id
     * @param type $user_id
     * @return boolean
     */
    public function deleteRow($id, $user_id)
    {
        $result = true;
        if (!is_null($id)) {
            $entity = $this->find('all')->where(['user_id' => $user_id])
                    ->where(['id' => $id])
                    ->first();
            if (!is_null($entity)) {
                if (!$this->delete($entity)) {
                    $result = false;
                }
            }
        }

        return $result;
    }




    /**
     *
     * @param type $id
     * @param type $user_id
     * @return boolean
     */
    public function editWatchlistGroup($user_id, $data)
    {
        $result = false;
        if (count($data)) {
            $entity = $this->find()
                    ->where(['user_id' => $user_id])
                    ->where(['id' => $data['id']])
                    ->first();
            if (!is_null($entity)) {
                $entity->name = $data['watch_list_name'];
                if ($this->save($entity)) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     *
     * @param type $user_id
     * @param type $data
     * @return boolean
     */
    public function createWatchlistGroup($user_id, $data)
    {
        $result = false;
        if (count($data)) {
            $entity = $this->newEntity();
            $entity->user_id = $user_id;
            $entity->name = $data['data'];
            return $this->save($entity);
        }

        return $result;
    }
    public function getList($userId)
    {
        $currentUser = $this->AppUsers->getUserAccountType($userId);

        $defaultWatchlist = $this->find('list')
            ->where([
                'WatchlistGroup.user_id' => $userId,
                'WatchlistGroup.is_default' => true
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
            'WatchlistGroup.user_id' => $userId,
            'WatchlistGroup.is_default' => false
        ]);

        $watchlists = $watchlists->toArray();

        return Hash::merge($defaultWatchlist, $watchlists);

    }

    
    /**
     * add method it will save a new watchlist group and validate if the user can create a new group.
     *
     *
     * @param \Cake\ORM\Entity $watchlist
     * @return array
     */
    public function add($watchlist)
    {
        $currentUser = $this->AppUsers->getUserAccountType($watchlist->user_id);
        if ($currentUser->account_type == 'FREE') {
            return 1;
        }

        if ($currentUser->account_type == 'INDIVIDUAL') {
            $total = $this->find()
                ->where(['WatchlistGroup.user_id' => $watchlist->user_id])
                ->count();

            if ($total >= 4) {
                return 2;
            }
        }

        $status = false;
        $message = 'We could not save your watchlist group. Please try again';
        if ($this->save($watchlist)) {
            $status = true;
            $message = 'Watchlist group is saved.';
        }

        return [
            'message' => $message,
            'watchlist' => $watchlist,
        ];
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
                'WatchlistGroup.user_id' => $userId,
                'WatchlistGroup.is_default' => true
            ])
            ->toArray();

        $watchlists = [];
        if ($currentUser->account_type != 'FREE') {

            $limit = null;
            if ($currentUser->account_type == 'INDIVIDUAL') {
                $limit = 3;
            }

            $watchlists = $this->find()
                ->where([
                    'WatchlistGroup.user_id' => $userId,
                    'WatchlistGroup.is_default' => false
                ])
                ->limit($limit)
                ->toArray();
        }

        $groups = Hash::merge($default, $watchlists);
        $ids = Hash::extract($groups, '{n}.id');

        return $this->find()
            ->where(['WatchlistGroup.id IN' => $ids]);
    }
}
