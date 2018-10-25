<?php
use Migrations\AbstractMigration;

class AddColumnToEventsTable extends AbstractMigration
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
        $table = $this->table('events');
        $table->addColumn('enable', 'integer', [
            'null' => true,
            'default' => null,
        ]);
        $table->update();
    }
}
