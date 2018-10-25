<?php
use Migrations\AbstractSeed;

/**
 * Countries seed.
 */
class CountriesSeed extends AbstractSeed
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
                'name'      => 'United States',
                'symbol'    => 'EUA',
                'market'    => 'USD',
                'created'   => date('Y-m-d H:i:s'),
                'modified'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'      => 'Jamaica',
                'symbol'    => 'JAM',
                'market'    => 'JMD',
                'created'   => date('Y-m-d H:i:s'),
                'modified'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'      => 'Trinidad e Tobago',
                'symbol'    => 'TRI',
                'market'    => 'TRI',
                'created'   => date('Y-m-d H:i:s'),
                'modified'  => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('countries');
        $table->truncate();
        $table->insert($data)->save();
    }
}
