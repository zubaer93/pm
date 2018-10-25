<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WatchlistBondItemsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WatchlistBondItemsTable Test Case
 */
class WatchlistBondItemsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\WatchlistBondItemsTable
     */
    public $WatchlistBondItems;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.watchlist_bond_items',
        'app.users',
        'app.groups'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('WatchlistBondItems') ? [] : ['className' => WatchlistBondItemsTable::class];
        $this->WatchlistBondItems = TableRegistry::get('WatchlistBondItems', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->WatchlistBondItems);

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
