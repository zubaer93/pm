<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\FrozenTime;

/**
 * Event Entity
 *
 * @property int $id
 * @property int $company_id
 * @property string $title
 * @property string $activity_type
 * @property string $description
 * @property \Cake\I18n\FrozenTime $time
 * @property \Cake\I18n\FrozenDate $date
 * @property string $location
 * @property string $meeting_link
 * @property \Cake\I18n\FrozenTime $created_at
 *
 * @property \App\Model\Entity\Company $company
 */
class Event extends Entity
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
    protected function _getShowDate()
    {
        $datetime = date('Y-m-d', strtotime($this->_properties['date']));
        if (!empty($this->_properties['time'])) {
            $datetime .= ' ' . date('H:s', strtotime($this->_properties['time']));
        }

        $datetime = new FrozenTime($datetime);

        return $datetime->nice();
    }
}
