<?php

use Migrations\AbstractMigration;

class NewsTable extends AbstractMigration
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
        $table = $this->table('news');
        $table->addColumn('source_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('source_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('author', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('title', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('description', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('url', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('urlToImage', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('publishedAt', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);

        $table->create();
    }

}
