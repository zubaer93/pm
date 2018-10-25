<?php
use Migrations\AbstractMigration;

class RateChange extends AbstractMigration
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
      $table = $this->table('rate');
      $table->addColumn('high', 'float', [
          'null' => true,
          'default' => null,
      ]);

      $table->addColumn('low', 'float', [
          'null' => true,
          'default' => null,
      ]);

      $table->update();
    }
}
