<?php
use Migrations\AbstractMigration;

class CreatePaymentLog extends AbstractMigration
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
        $table = $this->table('payment_log');
        $table->addColumn('payment_id', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('plan_id', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('amount', 'datetime');
        $table->addColumn('transaction_id', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('response_text', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('user_id', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('stripe_customer_id', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime');
        $table->create();
    }
}
