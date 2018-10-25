<?php

use Migrations\AbstractMigration;

class CreateSimulationSetting extends AbstractMigration
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
        $table = $this->table('simulation_setting');
        $table->addColumn('user_id', 'char', [
                    'default' => null,
                    'null' => true,
                ])
                ->addColumn('investment_amount', 'string', [
                    'default' => null,
                    'limit' => 50,
                    'null' => false,
                ])
                ->addColumn('quantity', 'string', [
                    'default' => null,
                    'limit' => 50,
                    'null' => false,
                ])
                ->addColumn('market', 'string', [
                    'default' => null,
                    'limit' => 255,
                    'null' => true,
                ])
                ->addColumn('broker_id', 'integer', [
                    'default' => null,
                    'null' => true,])
                ->addColumn('created_at', 'timestamp', [
                    'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->addForeignKey('broker_id', 'brokers', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);
        $table->create();
    }

}
