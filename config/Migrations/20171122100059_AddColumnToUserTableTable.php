<?php

use Migrations\AbstractMigration;

class AddColumnToUserTableTable extends AbstractMigration
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

        $table->addColumn('experince_id', 'integer');
        $table->addColumn('investment_style_id', 'integer');
        $table->addColumn('date_of_birth', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }

}
