<?php

use Migrations\AbstractMigration;

class CreateAnalysisTagsTable extends AbstractMigration
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
        $table = $this->table('analysis_tags');
        $table->addColumn('name', 'string', [
            'null' => true,
            'default' => null,
        ]);
        $table->addColumn('analysis_id', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->addForeignKey('analysis_id', 'analysis', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);
        $table->create();
    }

}
