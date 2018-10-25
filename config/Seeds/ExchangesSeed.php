<?php
use Migrations\AbstractSeed;

/**
 * ExchangesSeed seed.
 */
class ExchangesSeed extends AbstractSeed
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
                'name'          => 'Nasdaq',
                'country_id'    => 1,
                'created'       => date('Y-m-d H:i:s'),
                'modified'      => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'NYSE',
                'country_id'    => 1,
                'created'       => date('Y-m-d H:i:s'),
                'modified'      => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'AMEX',
                'country_id'    => 1,
                'created'       => date('Y-m-d H:i:s'),
                'modified'      => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Junior',
                'country_id'    => 2,
                'created'       => date('Y-m-d H:i:s'),
                'modified'      => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Main',
                'country_id'    => 2,
                'created'       => date('Y-m-d H:i:s'),
                'modified'      => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'USD',
                'country_id'    => 2,
                'created'       => date('Y-m-d H:i:s'),
                'modified'      => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Bond',
                'country_id'    => 2,
                'created'       => date('Y-m-d H:i:s'),
                'modified'      => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('exchanges');
        $table->truncate();
        $table->insert($data)->save();
    }
}
