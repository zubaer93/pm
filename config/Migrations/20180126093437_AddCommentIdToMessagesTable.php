<?php

use Migrations\AbstractMigration;

class AddCommentIdToMessagesTable extends AbstractMigration
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
        $table->addColumn('comment_id', 'integer', [
            'null' => true,
            'default' => null
        ]);
        $table->update();
    }

}
