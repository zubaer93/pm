<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AnalysisWatchListFixture
 *
 */
class AnalysisWatchListFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'analysis_watch_list';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'watch_list_group_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'analysis_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created_at' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'watch_list_group_id' => ['type' => 'index', 'columns' => ['watch_list_group_id'], 'length' => []],
            'analysis_id' => ['type' => 'index', 'columns' => ['analysis_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'analysis_watch_list_ibfk_1' => ['type' => 'foreign', 'columns' => ['watch_list_group_id'], 'references' => ['watchlist_group', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'analysis_watch_list_ibfk_2' => ['type' => 'foreign', 'columns' => ['analysis_id'], 'references' => ['analysis', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'watch_list_group_id' => 1,
            'analysis_id' => 1,
            'created_at' => 1518784549
        ],
    ];
}
