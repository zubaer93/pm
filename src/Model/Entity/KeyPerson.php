<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * KeyPerson Entity
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $age
 * @property int $company_id
 * @property string $photo
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Company $company
 */
class KeyPerson extends Entity
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
            if (file_exists(WWW_ROOT . 'files/KeyPeople/photo' . DS . $this->_properties['photo'])) {
                return '/files/KeyPeople/photo' . DS . $this->_properties['photo'];
            }
        }
        return 'no-user.png';
    }
}
