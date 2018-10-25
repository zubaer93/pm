<?php

namespace Api\Model\Table;

use Cake\ORM\Table;

/**
 * ApiRequests Model
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PaymentsTable extends Table
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

        $this->table('payments');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
    }

    public function savePayments($userId, $subscription){
        
        $data['user_id'] = $userId;
        $data['total_amount'] = $subscription['items']['data'][0]['plan']['amount'];
        $data['last_payment_date'] = $subscription['items']['data'][0]['created'];
        $data['last_paid_amount'] = $subscription['items']['data'][0]['plan']['amount'];
        $data['plan_id'] = $subscription['items']['data'][0]['plan']['id'];
        $data['stripe_customer_id'] = $subscription['customer'];
        $data['billing'] = $subscription['billing'];
        $data['billing_cycle_anchor'] = $subscription['billing_cycle_anchor'];
        $data['current_period_end'] = $subscription['current_period_end'];
        $data['current_period_start'] = $subscription['current_period_start'];

        $save_payment = $this->newEntity();
        $save_payment = $this->patchEntity($save_payment, $data);
        
        if($this->save($save_payment)){
            return true;
        }

    }
}
