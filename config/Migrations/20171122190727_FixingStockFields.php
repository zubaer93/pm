<?php
use Migrations\AbstractMigration;

class FixingStockFields extends AbstractMigration
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
        $table->changeColumn('information', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->changeColumn('symbol', 'string', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->changeColumn('last_refreshed', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->changeColumn('intervals', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->changeColumn('output_size', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->changeColumn('time_zone', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->changeColumn('time_series_date_time', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->changeColumn('open', 'float', [
            'default' => null,
            'null' => true,
        ]);
        $table->changeColumn('high', 'float', [
            'default' => null,
            'null' => true,
        ]);
        $table->changeColumn('low', 'float', [
            'default' => null,
            'null' => true,
        ]);
        $table->changeColumn('close', 'float', [
            'default' => null,
            'null' => true,
        ]);
        $table->changeColumn('volume', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->changeColumn('status', 'integer', [
            'default' => null,
            'limit' => 2,
            'null' => true,
        ]);
        $table->changeColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->changeColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
