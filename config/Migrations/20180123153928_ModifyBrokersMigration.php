<?php

use Migrations\AbstractMigration;

class ModifyBrokersMigration extends AbstractMigration
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
        $table = $this->table('brokers');

        $table->addColumn('exchange_fee', 'string', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('trade_fee', 'string', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('percent', 'integer', [
            'null' => false,
            'default' => 0,
        ]);

        $table->update();
    }

}
