<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Countries Model
 *
 * @property \App\Model\Table\ExchangesTable|\Cake\ORM\Association\HasMany $Exchanges
 *
 * @method \App\Model\Entity\Country get($primaryKey, $options = [])
 * @method \App\Model\Entity\Country newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Country[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Country|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Country patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Country[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Country findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ScreenshotMessageTable extends Table
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
        $this->setTable('screenshot_message');
    }

    public function getMessageData($message_id)
    {
        return $this->find()
                        ->where(['message_id' => $message_id])
                        ->first();
    }

    public function deleteRow($id)
    {
        $result = true;
        if (!is_null($id)) {
            $entity = $this->find()
                    ->where(['message_id' => $id])
                    ->first();
            if (!is_null($entity)) {
                if (!$this->delete($entity)) {
                    $result = false;
                }
            }
        }

        return $result;
    }

}
