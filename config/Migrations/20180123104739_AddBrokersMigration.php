<?php

use Migrations\AbstractMigration;

class AddBrokersMigration extends AbstractMigration
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
        $table = $this->table('brokers');

        $table->addColumn('first_name', 'string', [
                    'default' => null,
                    'limit' => 50,
                    'null' => false,
                ])
                ->addColumn('last_name', 'string', [
                    'default' => null,
                    'limit' => 50,
                    'null' => false,
                ])
                ->addColumn('fee', 'string', [
                    'default' => 0,
                    'null' => false,
                ])
                ->addColumn('market', 'string', [
                    'default' => null,
                    'limit' => 255,
                    'null' => true,
                ])
                ->addColumn('enable', 'integer', [
                            'null' => false,
                            'default' => 0,
                ]);

        $table->create();
    }

}
