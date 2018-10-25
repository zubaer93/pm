<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnalysisNewsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnalysisNewsTable Test Case
 */
class AnalysisNewsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AnalysisNewsTable
     */
    public $AnalysisNews;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.analysis_news',
        'app.news',
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
        $config = TableRegistry::exists('AnalysisNews') ? [] : ['className' => AnalysisNewsTable::class];
        $this->AnalysisNews = TableRegistry::get('AnalysisNews', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AnalysisNews);

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
