<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Analysi Entity
 *
 * @property int $id
 * @property string $user_id
 * @property int $company_id
 * @property string $text
 * @property \Cake\I18n\FrozenTime $created_at
 *
 * @property \App\Model\Entity\AppUser $user
 * @property \App\Model\Entity\Company $company
 */
class Analysi extends Entity
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
