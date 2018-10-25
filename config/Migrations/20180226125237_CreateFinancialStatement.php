<?php

use Migrations\AbstractMigration;

class CreateFinancialStatement extends AbstractMigration
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
        $table = $this->table('financial_statement');

        $table->addColumn('title', 'text', [
            'null' => true,
            'default' => null,
        ]);
        $table->addColumn('description', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('company_id', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('modified_at', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addForeignKey('company_id', 'companies', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);


        $table->create();
        /**
         * financial_statement_files
         */
        $table = $this->table('financial_statement_files');
        $table->addColumn('file_path', 'string', [
            'limit' => 255,
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('financial_statement_id', 'integer', [
            'default' => null,
            'null' => true
        ]);
        $table->addColumn('created_at', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('modified_at', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addForeignKey('financial_statement_id', 'financial_statement', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);


        $table->create();
    }

}
