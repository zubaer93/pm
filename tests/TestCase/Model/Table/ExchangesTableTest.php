<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExchangesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExchangesTable Test Case
 */
class ExchangesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExchangesTable
     */
    public $Exchanges;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.exchanges',
        'app.countries',
        'app.companies'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Exchanges') ? [] : ['className' => ExchangesTable::class];
        $this->Exchanges = TableRegistry::get('Exchanges', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Exchanges);

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
