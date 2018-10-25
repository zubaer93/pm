<?php

use Migrations\AbstractMigration;

class AddPartnersMigrationTable extends AbstractMigration
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
        $table = $this->table('partners');

        $table->addColumn('name', 'string', [
                    'limit' => 50,
                    'null' => false,
                ])
                ->addColumn('subdomain', 'string', [
                    'limit' => 50,
                    'null' => false,
                ])
                ->addIndex('subdomain', ['unique' => true])
                ->addColumn('logo_path', 'string', [
                    'default' => null,
                    'null' => true,
                    'limit' => 255
                ])
                ->addColumn('main_color', 'string', [
                    'default' => null,
                    'null' => true,
                    'limit' => 255
                ])
                ->addColumn('main_border_color', 'string', [
                    'default' => null,
                    'null' => true,
                    'limit' => 255
                ])
                ->addColumn('enable', 'integer', [
                    'null' => false,
                    'default' => 0,
        ]);

        $table->create();
    }

}
