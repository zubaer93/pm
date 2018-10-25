<?php
use Migrations\AbstractMigration;

class AddColumnToMessagesTable extends AbstractMigration
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
        $table->addColumn('market', 'string', [
            'null' => true,
            'default' => null,
            'limit' => 255,
        ]);
        $table->update();
    }
}
