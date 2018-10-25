<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * WatchlistBondItem Entity
 *
 * @property int $id
 * @property string $user_id
 * @property int $group_id
 * @property string $isin_code
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\AppUser $user
 * @property \App\Model\Entity\Group $group
 */
class WatchlistBondItem extends Entity
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
