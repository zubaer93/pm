<?php

use Migrations\AbstractMigration;

class AddEnableColumnToUserTable extends AbstractMigration
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
        $table->addColumn('enable', 'integer', [
            'null' => false,
            'default' => 0,
        ]);
       $table->update();
    }

}
