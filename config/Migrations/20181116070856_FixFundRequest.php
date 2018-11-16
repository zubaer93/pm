<?php
use Migrations\AbstractMigration;

class FixFundRequest extends AbstractMigration
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
        $table = $this->table('fund_request');
        $table->addColumn('admin_message', 'text', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('transaction_id', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('refund_method', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('refund_number', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('refund_transaction_id', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('refund_status', 'integer', [
            'limit' => 11,
            'default' => null,
            'null' => true
        ]);
        $table->save();
    }
}
