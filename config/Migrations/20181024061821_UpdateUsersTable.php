<?php
use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class UpdateUsersTable extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('address1', 'text', [
            'limit' => MysqlAdapter::TEXT_LONG
                ], [
            'default' => null
        ]);
        $table->addColumn('address2', 'text', [
            'limit' => MysqlAdapter::TEXT_LONG
                ], [
            'default' => null
        ]);
        $table->addColumn('city', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('state', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('zip', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->save();
    }
}
