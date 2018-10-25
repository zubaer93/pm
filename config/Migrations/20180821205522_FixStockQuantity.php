<?php
use Migrations\AbstractMigration;

class FixStockQuantity extends AbstractMigration
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
        $table = $this->table('stocks');
        $table->changeColumn('quantity', 'float', [
            'default' => 0,
            'null' => false,
        ])
        ->save();
    }
}
