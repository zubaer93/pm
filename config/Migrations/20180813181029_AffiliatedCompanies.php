<?php
use Migrations\AbstractMigration;

class AffiliatedCompanies extends AbstractMigration
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
        $table = $this->table('affiliated_companies');
        $table = $table->rename('affiliates_companies');

        $table = $this->table('affiliates');
        $table
            ->addColumn('name', 'string', [
                'limit' => 255,
                'null' => false
            ])
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true
            ])
            ->addColumn('website', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true
            ])
            ->addColumn('description', 'text', [
                'null' => true
            ])
            ->addColumn('date_of_incorporation', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('logo', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $table = $this->table('affiliates_companies');
        $table->renameColumn('affiliated_company_id', 'affiliate_id');
        $table->save();
    }
}
