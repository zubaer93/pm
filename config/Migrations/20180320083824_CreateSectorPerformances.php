<?php

use Migrations\AbstractMigration;

class CreateSectorPerformances extends AbstractMigration
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
        $table = $this->table('sector_performances');

        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('percent', 'string', [
            'default' => 0,
            'limit' => 255,
            'null' => true,
        ]);

        $table->create();
    }

}
