<?php
use Migrations\AbstractMigration;

class CreateNotifications extends AbstractMigration
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
        $table = $this->table('notifications');
      
        $table->addColumn('user_id', 'char', [
            'limit' => 36,
            'null' => false
        ]);
        
        $table->addColumn('title', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('url', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('seen', 'char', [
            'default' => 0,
            'limit' => 1,
            'null' => false,
        ]);
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->create();
    }
}
