<?php
use Migrations\AbstractMigration;

class AddPrivateTable extends AbstractMigration
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
        $table = $this->table('private');

        $table->addColumn('user_id', 'char',[
            'default' => null,
            'null' =>true,
            ])
            ->addColumn('name','string',[
                'default' => null,
                'null' =>true,
            ])
            ->addColumn('slug','string',[
                'default' => null,
                'null' =>true,
            ])
            ->addColumn('created_at','timestamp',[
                'default' => 'CURRENT_TIMESTAMP'
            ])
         ->addIndex(['name'], ['unique' => true]);

        $table->create();
    }
}
