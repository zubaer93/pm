<?php

use Migrations\AbstractMigration;

class AddBodyToNewsMigration extends AbstractMigration
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
        $table = $this->table('news');
        $table->addColumn('body', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }

}
