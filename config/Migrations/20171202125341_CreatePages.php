<?php

use Migrations\AbstractMigration;

class CreatePages extends AbstractMigration
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
        $table = $this->table('pages');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('body', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('slug', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('enable', 'integer', [
            'null' => false,
            'default' => 0,
        ]);
        $table->addColumn('position', 'integer', [
            'null' => true,
            'default' => null,
        ]);

        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        
        $table->create();
    }

}
