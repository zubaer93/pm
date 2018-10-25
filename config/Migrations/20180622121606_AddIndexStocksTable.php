<?php

use Migrations\AbstractMigration;

class AddIndexStocksTable extends AbstractMigration
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
        $table = $this->table('stocks');
        $table->changeColumn('symbol', 'string', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ])
        ->addIndex(['symbol']);
        // $table->changeColumn('company_id', 'integer', [
        //     'default' => null,
        //     'limit' => 11,
        //     'null' => false,
        // ])
        // ->addIndex(['company_id'])
        // ->addForeignKey('company_id', 'companies', 'id');
        $table->update();
    }

}
