<?php

use Migrations\AbstractMigration;

class AddTraderIdColumnToMessagesTable extends AbstractMigration
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
        $table = $this->table('messages');
        $table->addColumn('trader_id', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }

}
