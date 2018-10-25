<?php

namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * 
 */
class FollowTable extends Table
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

        $this->setTable('follow');
        $this->belongsTo('Follower', [
            'className' => 'AppUsers',
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Following', [
            'className' => 'AppUsers',
            'foreignKey' => 'follow_user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * 
     * @param type $user_id
     */
    public static function checkUserFollow($user_id, $follow_user_id)
    {
        $Follow = TableRegistry::get('Api.Follow');
        $data = $Follow->find();

        return (bool) $data->where(['user_id' => $user_id])
            ->where(['follow_user_id' => $follow_user_id])
            ->first();
    }

    /**
     * 
     * @param type $userName
     * @param type $user_id
     * @return boolean
     */
    public function setOrUpdate($follow_user_id, $user_id)
    {
        $result[0] = true;
        if (!is_null($follow_user_id) && !is_null($user_id)) {
            $entity = $this->find('all')
                    ->where(['user_id' => $user_id])
                    ->where(['follow_user_id' => $follow_user_id])
                    ->first();

            if (!is_null($entity)) {
                if (!$this->delete($entity)) {
                    $result[0] = false;
                }
                $result[1] = 'delete';
            } else {
                $entity = $this->newEntity();
                $entity->user_id = $user_id;
                $entity->follow_user_id = $follow_user_id;
                if (!$this->save($entity)) {
                    $result = false;
                }

                $result[1] = 'add';
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

}
