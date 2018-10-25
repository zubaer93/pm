<?php
use Migrations\AbstractMigration;

class CreateStocks extends AbstractMigration
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
        $table->addColumn('company_id', 'integer', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('information', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('symbol', 'string', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('last_refreshed', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('intervals', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('output_size', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('time_zone', 'string', [
            'default' => null,
			'limit' => 255,
            'null' => false,
        ]);
		$table->addColumn('time_series_date_time', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
		$table->addColumn('open', 'float', [
            'default' => null,
            'null' => false,
        ]);
		$table->addColumn('high', 'float', [
            'default' => null,
            'null' => false,
        ]);
		$table->addColumn('low', 'float', [
            'default' => null,
            'null' => false,
        ]);
		$table->addColumn('close', 'float', [
            'default' => null,
            'null' => false,
        ]);
		$table->addColumn('volume', 'integer', [
            'default' => null,
			'limit' => 11,
            'null' => false,
        ]);
		$table->addColumn('status', 'integer', [
            'default' => null,
			'limit' => 2,
            'null' => false,
        ]);
		$table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
