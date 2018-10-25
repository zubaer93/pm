<?php

use Migrations\AbstractMigration;

class CreateCompanyNewsTable extends AbstractMigration
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
        $table = $this->table('news_company');
        $table->addColumn('company_id', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('news_id', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('symbol', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }

}
