<?php

namespace App\Model\Table;

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

        if ($limit) {
            $watchlists->limit($limit);
        }

        $watchlists = $watchlists->toArray();

        return Hash::merge($defaultWatchlist, $watchlists);

    }

    /**
     * getWatchlists method It will return the default and last watchlist group created
     *
     * @param string $userId User id
     * @return array
     */
    public function getWatchlists($userId)
    {
        $currentUser = $this->AppUsers->getUserAccountType($userId);

        $defaultWatchlist = $this->find()
            ->where([
                'WatchlistGroup.user_id' => $userId,
                'WatchlistGroup.is_default' => true
            ])->first();

        $defaultId = null;
        if ($defaultWatchlist) {
            $defaultId = $defaultWatchlist->id;
        }

        $lastWatchlist = false;
        if ($currentUser->account_type != 'FREE') {
            $lastWatchlist = $this->find()
                ->where([
                    'WatchlistGroup.user_id' => $userId,
                ])
                ->order(['WatchlistGroup.created_at' => 'DESC'])
                ->limit(1)
                ->first();
        }

        $lastId = null;
        if ($lastWatchlist) {
            $lastId = $lastWatchlist->id;
        }

        return $this->find()
            ->where(['WatchlistGroup.id IN' => [$defaultId, $lastId]]);
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
