<?php
namespace Api\Model\Table;

use Api\Model\Table\GlobalAlertsTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EmailAlerts Model
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EmailAlertsTable extends Table
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

        $this->setTable('email_alerts');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('GlobalAlerts', [
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('TimeAlerts', [
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AppUsers', [
            'foreignKey' => 'user_id'
        ]);
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
        $rules->add($rules->existsIn(['global_alert_id'], 'GlobalAlerts'));
        $rules->add($rules->existsIn(['time_alert_id'], 'TimeAlerts'));
        $rules->add($rules->existsIn(['user_id'], 'AppUsers'));

        return $rules;
    }

    /**
     * getAllWatchlistNotifications method, it will return all users who have all watchlist notification enabled
     *
     * @return Cake\ORM\Query
     */
    public function getAllWatchlistNotifications()
    {
        return $this->find()
            ->where(['EmailAlerts.global_alert_id' => GlobalAlertsTable::ALL_WATCHLIST_NOTIFICATIONS])
            ->contain(['TimeAlerts' => function ($q) {
                return $q->where(['TimeAlerts.when_happens' => 1]);
            }]);
    }

    /**
     * getUsersForAllStocks method, it will return all users who have all stocks notification enabled
     *
     * @return Cake\ORM\Query
     */
    public function getAllStocksNotifications()
    {
        return $this->find()
            ->where(['EmailAlerts.global_alert_id' => GlobalAlertsTable::ALL_STOCK_NOTIFICATIONS])
            ->contain([
                'TimeAlerts' => function ($q) {
                    return $q->where(['TimeAlerts.when_happens' => 1]);
            },
            'AppUsers'
        ]);
    }
}
