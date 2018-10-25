<?php

use Migrations\AbstractMigration;

class CreateRateTable extends AbstractMigration
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
        $table = $this->table('rate');

        $table->addColumn('trader_id', 'integer', [
                    'default' => null,
                    'null' => true,
                ])
                ->addColumn('exchange_rate', 'string', [
                    'default' => null,
                    'limit' => 255,
                    'null' => true,
                ])
                ->addColumn('last_refreshed', 'datetime', [
                    'default' => null,
                    'null' => true,
                ])
                ->addColumn('created_at', 'timestamp', [
                    'default' => 'CURRENT_TIMESTAMP'
                ])
                ->addIndex(['trader_id'])
                ->addForeignKey('trader_id', 'trader', 'id');

        $table->create();
    }

}
