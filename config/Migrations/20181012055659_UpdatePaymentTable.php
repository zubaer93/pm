<?php
use Migrations\AbstractMigration;

class UpdatePaymentTable extends AbstractMigration
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
        $table = $this->table('payments');
        $table->addColumn('billing', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('billing_cycle_anchor', 'datetime');
        $table->addColumn('current_period_end', 'datetime');
        $table->addColumn('current_period_start', 'datetime');
        $table->save();
    }
}
