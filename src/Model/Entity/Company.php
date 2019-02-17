<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Company Entity
 *
 * @property int $id
 * @property string $name
 * @property string $symbol
 * @property int $ipoyear
 * @property string $sector
 * @property string $industry
 * @property int $exchange_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Exchange $exchange
 */
class Company extends Entity
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

    /**
     * _getShowPhoto method
     *
     * @return null|string
     */
    protected function _getShowPhoto()
    {
        if (!empty($this->_properties['photo'])) {
            if (file_exists(WWW_ROOT . 'files/Companies/photo' . DS . $this->_properties['photo'])) {
                return '/files/companies/photo' . DS . $this->_properties['photo'];
            }
        }
        return 'no-company.png';
    }
}
