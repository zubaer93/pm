<?php
use Migrations\AbstractMigration;

class FixingAlertTables extends AbstractMigration
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
        $table = $this->table('global_alerts');
        $table->changeColumn('created', 'datetime', ['null' => true]);
        $table->changeColumn('modified', 'datetime', ['null' => true])
            ->save();

        $table = $this->table('time_alerts');
        $table->changeColumn('created', 'datetime', ['null' => true]);
        $table->changeColumn('modified', 'datetime', ['null' => true])
            ->save();

        $table = $this->table('email_alerts');
        $table->changeColumn('created', 'datetime', ['null' => true]);
        $table->changeColumn('modified', 'datetime', ['null' => true])
            ->save();

        $table = $this->table('sms_alerts');
        $table->changeColumn('created', 'datetime', ['null' => true]);
        $table->changeColumn('modified', 'datetime', ['null' => true])
            ->save();
    }
}
