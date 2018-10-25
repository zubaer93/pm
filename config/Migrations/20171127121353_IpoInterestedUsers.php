<?php
use Migrations\AbstractMigration;

class IpoInterestedUsers extends AbstractMigration
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
        $table = $this->table('ipo_interested_users');

        $table->addColumn('app_user_id', 'char', [
            'limit' => 36,
            'null' => false
        ])
            ->addColumn('ipo_company_id', 'integer', [
                'null' => false
            ])
            ->addIndex(['app_user_id'])
            ->addIndex(['ipo_company_id'])
            ->addForeignKey('app_user_id', 'users', 'id')
            ->addForeignKey('ipo_company_id', 'ipo_company', 'id');

        $table->create();
    }
}
