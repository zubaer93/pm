<?php
use Migrations\AbstractSeed;

/**
 * InvestmentStyle seed.
 */
class InvestmentStyleSeed extends AbstractSeed
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
                'name'      => 'Not sure',
                'description'    => 'Not sure'
            ],
            [
                'name'      => 'Longterm',
                'description'    => 'Longterm'
            ],
            [
                'name'      => 'Shorterm',
                'description'    => 'Shorterm'
            ],
            [
                'name'      => 'Daytrader',
                'description'    => 'Daytrader'
            ],
        ];

        $table = $this->table('investment_style');
        $table->truncate();
        $table->insert($data)->save();
    }
}
