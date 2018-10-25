<?php
use Migrations\AbstractMigration;

class CreateEvents extends AbstractMigration
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
        $table->addColumn('company_id', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('title', 'string', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('activity_type', 'string', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('description', 'string', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('time', 'time', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('date', 'date', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('location', 'string', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('meeting_link', 'string', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->create();
    }
}
