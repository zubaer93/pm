<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FinancialStatementTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FinancialStatementTable Test Case
 */
class FinancialStatementTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FinancialStatementTable
     */
    public $FinancialStatement;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.financial_statement',
        'app.companies',
        'app.stocks',
        'app.exchanges',
        'app.countries',
        'app.financial_statement_files'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('FinancialStatement') ? [] : ['className' => FinancialStatementTable::class];
        $this->FinancialStatement = TableRegistry::get('FinancialStatement', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FinancialStatement);

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
