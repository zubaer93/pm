<?php

namespace App\Notifications\Email;

use App\Notifications\EmailAlert;
use App\Notifications\Alert;

/**
 * NewsEmailAlert class, reponsible to alert the users based on the news
 */
class NewsEmailAlert extends EmailAlert implements Alert
{
    /**
     * notify method, it will send email for the users to notify them
     *
     * @return void
     */
    public function notify($users, $currentRecord = null)
    {
        foreach ($users as $user) {
            $info = [
                'template' => 'news_notification',
                'subject' => 'Here is the latest update for your stock ' . $user->company->symbol,
                'viewVars' => [
                    'info' => $user,
                    'currentRecord' => $currentRecord
                ],
                'to' => $user->app_user->email
            ];

            $this->_sendEmail($info);
        }
    }
}
