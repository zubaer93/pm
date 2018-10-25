<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NewsCompanyTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NewsCompanyTable Test Case
 */
class NewsCompanyTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\NewsCompanyTable
     */
    public $NewsCompany;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.news_company'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('NewsCompany') ? [] : ['className' => NewsCompanyTable::class];
        $this->NewsCompany = TableRegistry::get('NewsCompany', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NewsCompany);

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
     * Test addorUpdateNewsCompany method
     *
     * @return void
     */
    public function testAddorUpdateNewsCompany()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test deleteEverythingWithThisId method
     *
     * @return void
     */
    public function testDeleteEverythingWithThisId()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test deleteIpoMarket method
     *
     * @return void
     */
    public function testDeleteIpoMarket()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
