<?php

use Migrations\AbstractMigration;

class AddColumnToCompanyTable extends AbstractMigration
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
        $table = $this->table('companies');

        $table->addColumn('enable', 'integer', [
            'null' => false,
            'default' => 0,
        ]);
        
        $table->update();
    }

}
