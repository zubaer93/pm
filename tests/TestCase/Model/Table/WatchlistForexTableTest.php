<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WatchlistForexTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WatchlistForexTable Test Case
 */
class WatchlistForexTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\WatchlistForexTable
     */
    public $WatchlistForex;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.watchlist_forex',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('WatchlistForex') ? [] : ['className' => WatchlistForexTable::class];
        $this->WatchlistForex = TableRegistry::get('WatchlistForex', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->WatchlistForex);

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
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
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
