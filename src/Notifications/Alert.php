<?php

namespace App\Notifications;

/**
 * Alert class, reponsible to alert the users based in their watchlists.
 */
interface Alert
{
    /**
     * notify, it should notify the users with the updates in the system.
     *
     * @param array $users Users to be notified
     * @return void
     */
    public function notify($users, $currentRecord = null);
}
