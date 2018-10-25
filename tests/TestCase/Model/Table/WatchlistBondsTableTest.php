<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WatchlistBondsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WatchlistBondsTable Test Case
 */
class WatchlistBondsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\WatchlistBondsTable
     */
    public $WatchlistBonds;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.watchlist_bonds',
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
        $config = TableRegistry::exists('WatchlistBonds') ? [] : ['className' => WatchlistBondsTable::class];
        $this->WatchlistBonds = TableRegistry::get('WatchlistBonds', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->WatchlistBonds);

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
