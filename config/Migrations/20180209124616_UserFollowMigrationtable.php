<?php
use Migrations\AbstractMigration;

class UserFollowMigrationtable extends AbstractMigration
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
        $table = $this->table('follow');
      
        $table->addColumn('user_id', 'char', [
            'limit' => 36,
            'null' => false
        ]);
        $table->addColumn('follow_user_id', 'char', [
            'limit' => 36,
            'null' => false
        ]);
        
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->create();
    }
}
