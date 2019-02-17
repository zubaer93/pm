<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Affiliate Entity
 *
 * @property int $id
 * @property string $name
 * @property string $Address
 * @property string $Website
 * @property string $Description
 * @property \Cake\I18n\FrozenTime $date_of_incorporation
 * @property string $logo
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Company[] $companies
 */
class Affiliate extends Entity
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
     * _getShowDate method it will show the date in a beauty way
     *
     * @return null|string
     */
    protected function _getShowDate()
    {
        if (!empty($this->_properties['date_of_incorporation'])) {
            return date('Y-m-d', strtotime($this->_properties['date_of_incorporation']));
        }

        return false;
    }

    /**
     * _getShowLogo method
     *
     * @return null|string
     */
    protected function _getShowLogo()
    {
        if (!empty($this->_properties['logo'])) {
            if (file_exists(WWW_ROOT . 'files/Affiliates/logo' . DS . $this->_properties['logo'])) {
                return '/files/Affiliates/logo' . DS . $this->_properties['logo'];
            }
        }
        return 'no-company.png';
    }
}
