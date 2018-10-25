<?php
use Migrations\AbstractMigration;

class AddColumnToTransactionTable extends AbstractMigration
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
        $table->addColumn('fees', 'float', [
            'null' => true,
            'default' => null,
        ]);
        $table->update();
    }
}
