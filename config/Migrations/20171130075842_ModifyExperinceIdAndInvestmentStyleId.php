<?php

use Migrations\AbstractMigration;

class ModifyExperinceIdAndInvestmentStyleId extends AbstractMigration
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
        $table = $this->table('users');

        $table->changeColumn('experince_id', 'integer', [
            'default' => null,
            'null' => true
        ]);

        $table->changeColumn('investment_style_id', 'integer', [
            'default' => null,
            'null' => true
        ]);

        $table->save();
    }

}
