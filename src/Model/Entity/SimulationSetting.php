<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SimulationSetting Entity
 *
 * @property int $id
 * @property string $investment_amount
 * @property string $quantity
 * @property string $market
 * @property int $broker_id
 * @property \Cake\I18n\FrozenTime $created_at
 *
 * @property \App\Model\Entity\Broker $broker
 */
class SimulationSetting extends Entity
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
