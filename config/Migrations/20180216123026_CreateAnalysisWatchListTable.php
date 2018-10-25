<?php

use Migrations\AbstractMigration;

class CreateAnalysisWatchListTable extends AbstractMigration
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
        $table = $this->table('analysis_watch_list');
        $table->addColumn('watch_list_group_id', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('analysis_id', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->addForeignKey('watch_list_group_id', 'watchlist_group', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);
        $table->addForeignKey('analysis_id', 'analysis', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);
        $table->create();
    }

}
