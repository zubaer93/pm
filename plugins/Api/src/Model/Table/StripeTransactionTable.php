<?php

namespace Api\Model\Table;

use Cake\ORM\Table;

/**
 * ApiRequests Model
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StripeTransactionTable extends Table
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

        $this->table('stripe_transaction_table');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
    }

    public function saveTransaction($requestData){
        
        $data['charge_id'] = $requestData['data']['object']['id'];
        $data['transaction_id'] = $requestData['data']['object']['balance_transaction'];
        $data['amount'] = $requestData['data']['object']['amount'];
        $data['amount_refunded'] = $requestData['data']['object']['amount_refunded'];
        $data['currency'] = $requestData['data']['object']['currency'];
        $data['customer'] = $requestData['data']['object']['customer'];
        $data['invoice_id'] = $requestData['data']['object']['invoice'];
        $data['network_status'] = $requestData['data']['object']['outcome']['network_status'];
        $data['risk_level'] = $requestData['data']['object']['outcome']['risk_level'];
        $data['risk_score'] = $requestData['data']['object']['outcome']['risk_score'];
        $data['seller_message'] = $requestData['data']['object']['outcome']['seller_message'];
        $data['outcome_type'] = $requestData['data']['object']['outcome']['type'];
        $data['card_id'] = $requestData['data']['object']['source']['id'];
        $data['card_brand'] = $requestData['data']['object']['source']['brand'];
        $data['card_country'] = $requestData['data']['object']['source']['country'];
        $data['card_exp_month'] = $requestData['data']['object']['source']['exp_month'];
        $data['card_exp_year'] = $requestData['data']['object']['source']['exp_year'];
        $data['card_funding'] = $requestData['data']['object']['source']['funding'];
        $data['last4digit'] = $requestData['data']['object']['source']['last4'];
        $data['charge_status'] = $requestData['data']['object']['status'];
        $data['type'] = $requestData['type'];
        $data['created'] = $requestData['data']['object']['created'];
        $data['modified'] = $requestData['data']['object']['created'];
       
        $save_trans = $this->newEntity();
        $save_trans = $this->patchEntity($save_trans, $data);       

        if ($this->save($save_trans)) {
            return true;
        }
       
    }
}
