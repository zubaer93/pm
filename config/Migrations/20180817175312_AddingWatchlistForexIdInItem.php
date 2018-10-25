<?php
use Migrations\AbstractMigration;

class AddingWatchlistForexIdInItem extends AbstractMigration
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
        $table = $this->table('watchlist_forex_items');
        $table->addColumn('watchlist_forex_id', 'integer', [
            'limit' => 11,
            'null' => false,
        ]);
        $table->save();
    }
}
