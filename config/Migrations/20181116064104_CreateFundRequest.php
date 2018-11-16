<?php
use Migrations\AbstractMigration;

class CreateFundRequest extends AbstractMigration
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
        $table->addColumn('plan_id', 'integer', [
            'limit' => 11,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('user_id', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('status', 'integer', [
            'limit' => 11,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('transaction_method', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('description', 'text', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('request_date', 'datetime', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('transfer_date', 'datetime', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('refund_date', 'datetime', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime');
        $table->create();
    }
}
