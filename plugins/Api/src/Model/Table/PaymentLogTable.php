<?php

namespace Api\Model\Table;

use Cake\ORM\Table;

/**
 * ApiRequests Model
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PaymentLogTable extends Table
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

        $this->table('payment_log');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
    }
    public function savePaymentLog($userId, $subscription){
        
        $log['payment_id'] = $subscription['id'];
        $log['plan_id'] = $subscription['items']['data'][0]['plan']['id'];
        $log['amount'] =  $subscription['items']['data'][0]['plan']['amount'];
        $log['transaction_id'] = $subscription['id'];
        $log['response_text'] = json_encode($subscription);
        $log['user_id'] = $userId;
        $log['stripe_customer_id'] = $subscription['customer'];

        $save_log = $this->newEntity();
        $save_log = $this->patchEntity($save_log, $log);
        
        if($this->save($save_log)){
            return true;
        }

    }
}
