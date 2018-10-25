<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WatchlistTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WatchlistTable Test Case
 */
class WatchlistTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\WatchlistTable
     */
    public $Watchlist;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.watchlist',
        'app.users',
        'app.companies',
        'app.exchanges',
        'app.countries'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Watchlist') ? [] : ['className' => WatchlistTable::class];
        $this->Watchlist = TableRegistry::get('Watchlist', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Watchlist);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
