<?php
use Migrations\AbstractMigration;

class FixCreditPlansTable extends AbstractMigration
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
        $table = $this->table('credit_plans');
        $table->addColumn('plan_name', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->save();
    }
}
