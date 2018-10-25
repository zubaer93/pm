<?php

namespace App\Notifications;

use App\Notifications\Alert;

/**
 * SmsAlert class, reponsible to alert the users based in their watchlists.
 */
abstract class SmsAlert
{
    /**
     * _sendSms method, it will send email for the users to notify them
     *
     * @param array $info 
     * @return void
     */
    protected function _sendSms($info)
    {
        $email = new Email('default');
        $email
            ->from(['pennyintelligence@stockgitter.com' => 'Stockgitter'])
            ->to($info['to'])
            ->subject($info['subject'])
            ->template($info['template'])
            ->viewVars($info['viewVars'])
            ->emailFormat('html')
            ->send();
    }
    
}
