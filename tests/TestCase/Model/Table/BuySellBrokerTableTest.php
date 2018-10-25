<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BuySellBrokerTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BuySellBrokerTable Test Case
 */
class BuySellBrokerTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BuySellBrokerTable
     */
    public $BuySellBroker;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.buy_sell_broker',
        'app.companies',
        'app.stocks',
        'app.exchanges',
        'app.countries',
        'app.brokers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('BuySellBroker') ? [] : ['className' => BuySellBrokerTable::class];
        $this->BuySellBroker = TableRegistry::get('BuySellBroker', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BuySellBroker);

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
