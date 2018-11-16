<?php
use Migrations\AbstractMigration;

class CreateCreditPlansTable extends AbstractMigration
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
        $table->addColumn('amount', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('perion', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('payback_amount', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('description', 'text', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('eligible_catagory', 'string', [
            'limit' => 255,
            'default' => 1,
            'null' => true
        ]);
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime');
        $table->create();
    }
}
