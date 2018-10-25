<?php
use Migrations\AbstractMigration;

class EditSubscriptionPlans extends AbstractMigration
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
        $table->addColumn('interval', 'string', [
            'limit' => 2048,
            'null' => true,
        ]);
        $table->save();
    }
}
