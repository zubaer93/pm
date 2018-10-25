<?php
use Migrations\AbstractMigration;

class ResearchCompany extends AbstractMigration
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
        $table = $this->table('research_company');

        $table->addColumn('research_market_id', 'integer', [
            'default' => null,
            'null' => true,
        ])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('about', 'text', [
                'default' => null,
                'null' => true,
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
            ->addIndex(['research_market_id'])
            ->addForeignKey('research_market_id', 'research_market', 'id')
            ->addIndex(['name', 'slug'], ['unique' => true]);

        $table->create();
    }
}
