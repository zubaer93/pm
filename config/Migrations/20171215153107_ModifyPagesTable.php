<?php

use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
class ModifyPagesTable extends AbstractMigration
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
        $table = $this->table('pages');

        $table->changeColumn('body', 'text', [
            'limit' => MysqlAdapter::TEXT_LONG
                ], [
            'default' => null
        ]);
        $table->save();
    }

}
