<?php
use Migrations\AbstractMigration;

class WatchlistBondAndForex extends AbstractMigration
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
        $table = $this->table('watchlist_bonds');
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('user_id', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'null' => false,
        ]);
        $table->create();

        $table = $this->table('watchlist_bond_items');
        $table->addColumn('user_id', 'char', [
            'limit' => 36,
            'null' => false,
        ]);
        $table->addColumn('watchlist_bond_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('isin_code', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'null' => false,
        ]);
        $table->create();

        $table = $this->table('watchlist_forex');
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('user_id', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'null' => false,
        ]);
        $table->create();

        $table = $this->table('watchlist_forex_items');
        $table->addColumn('user_id', 'char', [
            'limit' => 36,
            'null' => false,
        ]);

        $table->addColumn('trader_id', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'null' => false,
        ]);
        $table->create();

        $table = $this->table('watchlist');
        $table->removeColumn('trader_id');
        $table->removeColumn('subdomain')
            ->save();
    }
}
