<?php
use Migrations\AbstractMigration;

class UpdateUserTable extends AbstractMigration
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
        $table = $this->table('users');
        
        $table->addColumn('nid', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('verifyPhoto', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('statement1', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('statement2', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('statement3', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('imei', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('statement4', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('utility_bill', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('address_proof', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->save();
    }
}
