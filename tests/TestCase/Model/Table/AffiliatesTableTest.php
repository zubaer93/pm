<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AffiliatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AffiliatesTable Test Case
 */
class AffiliatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AffiliatesTable
     */
    public $Affiliates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.affiliates',
        'app.companies',
        'app.stocks',
        'app.exchanges',
        'app.countries',
        'app.stocks_details',
        'app.key_people',
        'app.affiliates_companies'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Affiliates') ? [] : ['className' => AffiliatesTable::class];
        $this->Affiliates = TableRegistry::get('Affiliates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Affiliates);

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
}
