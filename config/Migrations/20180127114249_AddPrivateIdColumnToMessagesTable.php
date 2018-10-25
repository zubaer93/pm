<?php
use Migrations\AbstractMigration;

class AddPrivateIdColumnToMessagesTable extends AbstractMigration
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
        $table->addColumn('private_id', 'integer', [
            'default' => null,
            'null' => true,
        ]);

        $table->addForeignKey('private_id', 'private', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->update();
    }
}
