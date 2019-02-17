<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * WatchlistForexItem Entity
 *
 * @property int $id
 * @property string $user_id
 * @property int $group_id
 * @property string $trader_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\AppUser $user
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\Trader $trader
 */
class WatchlistForexItem extends Entity
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
