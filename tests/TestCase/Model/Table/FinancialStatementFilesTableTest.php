<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FinancialStatementFilesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FinancialStatementFilesTable Test Case
 */
class FinancialStatementFilesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FinancialStatementFilesTable
     */
    public $FinancialStatementFiles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.financial_statement_files',
        'app.financial_statement',
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
        $config = TableRegistry::exists('FinancialStatementFiles') ? [] : ['className' => FinancialStatementFilesTable::class];
        $this->FinancialStatementFiles = TableRegistry::get('FinancialStatementFiles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FinancialStatementFiles);

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
