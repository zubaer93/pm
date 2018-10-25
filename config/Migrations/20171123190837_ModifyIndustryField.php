<?php
use Migrations\AbstractMigration;

class ModifyIndustryField extends AbstractMigration
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
        $table = $this->table('companies');
        $table->changeColumn('industry', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true])->save();
    }
}
