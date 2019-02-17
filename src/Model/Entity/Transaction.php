<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Transaction Entity
 *
 * @property int $id
 * @property float $price
 * @property float $total
 * @property string $client_name
 * @property int $investment_amount
 * @property int $company_id
 * @property int $investment_preferences
 * @property string $market
 * @property int $quantity_to_buy
 * @property int $broker
 * @property int $action
 * @property int $order_type
 * @property string $user_id
 * @property \Cake\I18n\FrozenTime $created_at
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\AppUser $user
 */
class Transaction extends Entity
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
