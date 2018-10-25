<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnalysisSymbolsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnalysisSymbolsTable Test Case
 */
class AnalysisSymbolsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AnalysisSymbolsTable
     */
    public $AnalysisSymbols;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.analysis_symbols',
        'app.analysis',
        'app.app_users',
        'app.social_accounts',
        'app.users',
        'app.ipo_interested_users',
        'app.ipo_company',
        'app.ipo_market',
        'app.notifications',
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
        $config = TableRegistry::exists('AnalysisSymbols') ? [] : ['className' => AnalysisSymbolsTable::class];
        $this->AnalysisSymbols = TableRegistry::get('AnalysisSymbols', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AnalysisSymbols);

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
