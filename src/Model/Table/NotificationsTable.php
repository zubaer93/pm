<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Table;

use Cake\ORM\Table;
/**
 * Description of NotificationsTable
 *
 * @author Zevs
 */
class NotificationsTable extends Table {
    
    public function initialize(array $config)
    {
        parent::initialize($config);
        
        $this->belongsTo('AppUsers', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }
    
    public function setNotification($data)
    {
        $result = true;
        
        if (!empty($data)) {
            $notifications = $this->newEntity();
            $notifications->user_id = $data->user_id;
            $notifications->url = $data->url;
            $notifications->title = $data->title;

            if (!$this->save($notifications)) {
                $result = false;
            }
        }

        return $result;
    }
    
    public function changeSeenStatus($id,$status = 1){
        
        $notification = $this->get($id);
        $notification->seen = $status;
        if ($this->save($notification)) {
            return TRUE;
        }
        return FALSE;
    }
}
