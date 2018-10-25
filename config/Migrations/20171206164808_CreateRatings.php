<?php

use Migrations\AbstractMigration;

class CreateRatings extends AbstractMigration
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
        $table = $this->table('ratings');

        $table->addColumn('user_id', 'char', [
            'limit' => 36,
            'null' => false
        ]);
        $table->addColumn('message_id', 'integer', [
            'null' => false
        ]);
        $table->addColumn('grade', 'integer', [
            'null' => false
        ]);
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);

        $table->create();
    }

}
