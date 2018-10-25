<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StocksDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StocksDetailsTable Test Case
 */
class StocksDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StocksDetailsTable
     */
    public $StocksDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.stocks_details',
        'app.companies',
        'app.stocks',
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
        $config = TableRegistry::exists('StocksDetails') ? [] : ['className' => StocksDetailsTable::class];
        $this->StocksDetails = TableRegistry::get('StocksDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StocksDetails);

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
