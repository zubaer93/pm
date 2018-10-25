<?php
use Migrations\AbstractMigration;

class SubscriptionPlan extends AbstractMigration
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
        $table = $this->table('subscription_plan');
        $table->addColumn('stripe_id', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('product_id', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('price', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('interval_period', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime');
        $table->create();
    }
}
