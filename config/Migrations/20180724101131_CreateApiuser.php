<?php
use Migrations\AbstractMigration;

class CreateApiuser extends AbstractMigration
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
        $table = $this->table('api_users');
        $table->addColumn('user_id', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('client_id', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('client_secret', 'string', [
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('deleted_at', 'datetime',[
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('created', 'datetime');
        $table->addColumn('modified', 'datetime');
        $table->create();
    }
}
