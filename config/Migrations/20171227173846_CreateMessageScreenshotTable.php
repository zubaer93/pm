<?php

use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateMessageScreenshotTable extends AbstractMigration
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
        $table = $this->table('screenshot_message');

        $table->addColumn('data', 'text', [
            'limit' => MysqlAdapter::TEXT_LONG
                ], [
            'default' => null
        ]);
        $table->addColumn('message_id', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->addIndex(['message_id'])
                ->addForeignKey('message_id', 'messages', 'id');
        $table->create();
    }

}
