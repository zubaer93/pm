<?php

use Migrations\AbstractMigration;

class AddApproveToAnalysisi extends AbstractMigration
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
        $table = $this->table('analysis');
        $table->addColumn('approve', 'string', [
            'default' => 0,
            'limit' => 255,
            'null' => false,
        ]);
        $table->update();
    }

}
