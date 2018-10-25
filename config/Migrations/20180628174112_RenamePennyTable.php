<?php
use Migrations\AbstractMigration;

class RenamePennyTable extends AbstractMigration
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
        $table = $this->table('pennys');
        $table->rename('settings');

        $table = $this->table('settings');
        $table->renameColumn('enabled', 'enabled_penny');
    }
}
