<?php

use Migrations\AbstractMigration;

class CreateWatchlistGroupTable extends AbstractMigration
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
        $table = $this->table('watchlist_group');

        $table->addColumn('user_id', 'string', [
            'null' => false,
        ]);
        $table->addColumn('name', 'string', [
            'null' => true,
            'default' => null,
        ]);
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);

        $table->create();

        $table = $this->table('watchlist');
        $table->addColumn('group_id', 'integer', [
            'null' => true,
            'default' => null
        ]);
        $table->update();
    }

}
