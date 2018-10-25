<?php

namespace Api\Model\Table;

use Cake\ORM\Table;

class UserInviteTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);

        $this->belongsTo('AppUsers', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    public function setUserInvite($data) {
        $result = true;
        if (!empty($data)) {
            foreach ($data['user_id'] as $user_id) {
                $private = $this->newEntity();
                $private->user_id = $user_id;
                $private->invite_id = $data['id'];
                if (!$this->save($private)) {
                    $result = false;
                }
            }
        }

        return $result;
    }

    public function update($data) {

        try
        {
            $id = $data['id'];
            $search_array = $this->find()->where(['invite_id' => $id])->toArray();

            foreach ($search_array as $search) {
                //echo"<pre>";
                $key = array_search(trim($search->user_id), $data['private_user']);

                if ($key || $key === 0) {

                    unset($data['private_user'][$key]);
                } else {
                    $entity = $this->get($search->id);
                    $result = $this->delete($entity);
                }
            }
            foreach ($data['private_user'] as $val) {
                $private = $this->newEntity();
                $private->user_id = $val;
                $private->invite_id = $id;
                $this->save($private);
            }
        } catch (\Exception $exception)
        {
           $this->log($exception->getMessage());
        }
    }

}
