<?php

namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * Message Entity
 *
 * @property int $id
 * @property string $message
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $user_id
 *
 * @property \App\Model\Entity\AppUser $user
 */
class Message extends Entity
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
        'id' => false,
    ];
    
    /**
     * _getMessageParentMessage method this method will get message parent message.
     *
     * @return bool|array
     */
    protected function _getMessageParentMessage()
    {
        $bio = null;
        if (!empty($this->_properties['description'])) {
            $bio = $this->_properties['description'];
        } else if (!empty($this->_properties['social_accounts'][0])) {
            $bio = $this->_properties['social_accounts'][0]['description'];
        }
        return $bio;
    }
    
}
