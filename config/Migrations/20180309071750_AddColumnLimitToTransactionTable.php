<?php
use Migrations\AbstractMigration;

class AddColumnLimitToTransactionTable extends AbstractMigration
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
        $table = $this->table('transactions');
        $table->addColumn('limit_price', 'float', [
            'null' => true,
            'default' => null,
        ]);
        $table->addColumn('status', 'integer', [
            'null' => true,
            'default' => null,
        ]);
        $table->update();
    }
}
