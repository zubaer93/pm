<?php
use Migrations\AbstractMigration;

class CreateSimulationsTable extends AbstractMigration
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
        $table = $this->table('simulations');
        $table->addColumn('price', 'float', [
            'null' => true,
            'default' => null,
        ]);
        $table->addColumn('simulations_symbols', 'string', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('watchlist_id', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('company_id', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('user_id', 'char', [
            'default' => null,
            'limit' => 36,
            'null' => false,
        ]);
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);

        $table->addForeignKey('watchlist_id', 'watchlist', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);
        $table->create();
    }
}
