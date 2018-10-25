<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NewsCompaniesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NewsCompaniesTable Test Case
 */
class NewsCompaniesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\NewsCompaniesTable
     */
    public $NewsCompanies;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.news_companies',
        'app.companies',
        'app.stocks',
        'app.exchanges',
        'app.countries',
        'app.news'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('NewsCompanies') ? [] : ['className' => NewsCompaniesTable::class];
        $this->NewsCompanies = TableRegistry::get('NewsCompanies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NewsCompanies);

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
