<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Payment Entity
 *
 * @property int $id
 * @property string $user_id
 * @property string $total_amount
 * @property \Cake\I18n\FrozenTime $last_payment_date
 * @property string $last_paid_amount
 * @property string $plan_id
 * @property string $stripe_customer_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\AppUser $user
 * @property \App\Model\Entity\Plan $plan
 * @property \App\Model\Entity\StripeCustomer $stripe_customer
 * @property \App\Model\Entity\PaymentLog[] $payment_log
 */
class Payment extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
