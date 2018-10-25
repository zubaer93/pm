<?php
use Migrations\AbstractSeed;

/**
 * Experience seed.
 */
class ExperienceSeed extends AbstractSeed
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
                'name'      => 'Curious',
                'description'    => 'Curious'
            ],
            [
                'name'      => 'Beginner',
                'description'    => 'Beginner'
            ],
            [
                'name'      => 'Intermediate',
                'description'    => 'Intermediate'
            ],
            [
                'name'      => 'Expert',
                'description'    => 'Expert'
            ],
        ];

        $table = $this->table('experince');
        $table->truncate();
        $table->insert($data)->save();
    }
}
