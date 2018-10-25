<?php

use Migrations\AbstractMigration;

class ModifyStocksTable extends AbstractMigration
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

        $table->addColumn('price_change', 'float', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('percentage_change', 'float', [
            'default' => null,
            'null' => true,
        ]);

        $table->update();
    }

}
