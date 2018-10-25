<?php

use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateAnalysisTable extends AbstractMigration
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
        $table = $this->table('analysis');
        $table->addColumn('user_id', 'char', [
                    'default' => null,
                    'null' => true,
                ])
                ->addColumn('company_id', 'integer', [
                    'default' => null,
                    'null' => true
                ])
                ->addColumn('text', 'text', [
                    'limit' => MysqlAdapter::TEXT_LONG
                        ], [
                    'default' => null
                ])
                ->addColumn('created_at', 'timestamp', [
                    'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->addForeignKey('company_id', 'companies', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);
        $table->create();
    }

}
