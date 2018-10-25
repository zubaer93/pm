<?php

namespace App\Notifications;

use App\Notifications\Alert;
use Cake\Mailer\Email;
use EmailQueue\EmailQueue;

/**
 * EmailAlert class, reponsible to alert the users based in their watchlists.
 */
abstract class EmailAlert
{
    /**
     * _sendEmail method, it will send an email for the users to notify them
     *
     * @param array $info 
     * @return void
     */
    protected function _sendEmail($info)
    {
        EmailQueue::enqueue($info['to'], $info['viewVars'], $info);
    }
}
