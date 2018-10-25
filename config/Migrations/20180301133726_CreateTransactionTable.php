<?php
use Migrations\AbstractMigration;

class CreateTransactionTable extends AbstractMigration
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
        $table->addColumn('price', 'float', [
            'null' => true,
            'default' => null,
        ]);
        $table->addColumn('total', 'float', [
            'null' => true,
            'default' => null,
        ]);
        $table->addColumn('client_name', 'string', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('investment_amount', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('company_id', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('investment_preferences', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('market', 'string', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('quantity_to_buy', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('broker', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('action', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('order_type', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('user_id', 'char', [
            'default' => null,
            'limit' => 36,
            'null' => false,
        ]);
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->create();
    }
}
