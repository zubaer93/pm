<?php
use Migrations\AbstractMigration;

class FixSubscriptionField extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $users = $this->table('users');
        $users->changeColumn('account_type', 'string', [
            'default' => 'FREE',
            'limit' => 255,
            'null' => false
        ])
        ->save();
    }
}
