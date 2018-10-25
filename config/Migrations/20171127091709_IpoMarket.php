<?php
use Migrations\AbstractMigration;

class IpoMarket extends AbstractMigration
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
        $table = $this->table('ipo_market');

        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
        ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('order', 'integer', [
                'default' => 0,
                'null' => false,
            ])
            ->addColumn('status', 'string', [
                'default' => 'enabled',
                'limit' => 30,
                'null' => false,
            ])
            ->addIndex(['name', 'slug'], ['unique' => true]);

        $table->create();
    }
}
