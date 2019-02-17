<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GlobalAlerts Model
 *
 * @method \App\Model\Entity\GlobalAlerts get($primaryKey, $options = [])
 * @method \App\Model\Entity\GlobalAlerts newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GlobalAlerts[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GlobalAlerts|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GlobalAlerts patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GlobalAlerts[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GlobalAlerts findOrCreate($search, callable $callback = null, $options = [])
 */
class GlobalAlertsTable extends Table
{

    /**
     * CONSTANTS
     */
    const ALL_WATCHLIST_NOTIFICATIONS = 1;
    const ALL_STOCK_NOTIFICATIONS = 2;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('global_alerts');
        $this->addBehavior('Timestamp');

        $this->hasMany('EmailAlerts');
        $this->hasMany('SmsAlerts');
    }
}
