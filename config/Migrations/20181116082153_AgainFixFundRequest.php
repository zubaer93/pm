<?php
use Migrations\AbstractMigration;

class AgainFixFundRequest extends AbstractMigration
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
        $table->addColumn('actual_refund_date', 'datetime', [
            'default' => null,
            'null' => true
        ]);
        $table->save();
    }
}
