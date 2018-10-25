<?php

use Migrations\AbstractMigration;

class ModifySymbolToStocks extends AbstractMigration
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

        $table->changeColumn('symbol','string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->save();
    }

}
