<?php

namespace App\Notifications\Sms;

use App\Notifications\SmsAlert;
use App\Notifications\Alert;

/**
 * EventsSmsAlert class, reponsible to alert the users based on the news
 */
class EventsSmsAlert extends SmsAlert implements Alert
{
    /**
     * notify method, it will send email for the users to notify them
     *
     * @return void
     */
    public function notify($users, $currentRecord = null)
    {
        
    }
    
}
