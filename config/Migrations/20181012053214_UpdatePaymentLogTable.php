<?php
use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class UpdatePaymentLogTable extends AbstractMigration
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

        $table->changeColumn('response_text', 'text', [
            'limit' => MysqlAdapter::TEXT_LONG
                ], [
            'default' => null
        ]);
        $table->save();
    }
}
