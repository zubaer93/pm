<?php
use Migrations\AbstractMigration;

class ModifyMessagesTable extends AbstractMigration
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
        $table->removeColumn('market');
        $table->addColumn('country_id', 'integer', [
            'null' => true,
            'default' => null,
        ]);
        $table->update();
    }
}
