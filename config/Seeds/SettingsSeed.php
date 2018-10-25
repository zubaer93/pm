\<?php
use Migrations\AbstractSeed;

/**
 * Settings seed.
 */
class SettingsSeed extends AbstractSeed
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
    	$now = new \DateTime();

        $data = [
            'enabled_penny' => true,
            'created' => $now->format('Y-m-d H:s:i'),
            'modified' => $now->format('Y-m-d H:s:i')
        ];

        $table = $this->table('settings');
        $table->truncate();
        $table->insert($data)->save();
    }
}
