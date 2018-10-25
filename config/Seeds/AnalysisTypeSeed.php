<?php

use Migrations\AbstractSeed;

/**
 * AnalysisType seed.
 */
class AnalysisTypeSeed extends AbstractSeed
{

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Stock',
                'description' => 'Stock',
                'created_at' => date('Y-m-d H:i:s'),
            ], [
                'name' => 'Mutual Fund',
                'description' => 'Mutual Fund',
                'created_at' => date('Y-m-d H:i:s'),
            ], [
                'name' => 'Bond',
                'description' => 'Bond',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'name' => 'Treasury',
                'description' => 'Treasury',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('analysis_type');
        $table->truncate();

        $table->insert($data)->save();
    }

}
