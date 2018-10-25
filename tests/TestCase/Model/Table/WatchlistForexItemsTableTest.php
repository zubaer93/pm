<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WatchlistForexItemsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WatchlistForexItemsTable Test Case
 */
class WatchlistForexItemsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\WatchlistForexItemsTable
     */
    public $WatchlistForexItems;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.watchlist_forex_items',
        'app.users',
        'app.groups',
        'app.traders'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('WatchlistForexItems') ? [] : ['className' => WatchlistForexItemsTable::class];
        $this->WatchlistForexItems = TableRegistry::get('WatchlistForexItems', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->WatchlistForexItems);

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
