<?php

namespace Api\Model\Table;

use Cake\ORM\Table;

/**
 * ApiRequests Model
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FundRequestTable extends Table
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

        $this->table('fund_request');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        
        $this->belongsTo('AppUsers')
            ->setForeignKey('user_id')
            ->setJoinType('INNER');
        
        $this->belongsTo('CreditPlans')
            ->setForeignKey('plan_id')
            ->setJoinType('INNER');
    }
}
