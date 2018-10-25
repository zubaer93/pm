<?php
use Migrations\AbstractMigration;
use Cake\ORM\TableRegistry;

class SubscriptionField extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('account_type', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->save();

        $this->__setFreeAccountForAll();
    }

    /**
     * __setFreeAccountForAll method it will set free account for all current users
     *
     * @return void
     */
    private function __setFreeAccountForAll()
    {
        $AppUsers = TableRegistry::get('AppUsers');
        $AppUsers->removeBehavior('User');

        $users = $AppUsers->find();

        foreach ($users as $user) {
            $user->account_type = 'FREE';
            $AppUsers->save($user);
        }
    }
}
