<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\KeyPeopleTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\KeyPeopleTable Test Case
 */
class KeyPeopleTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\KeyPeopleTable
     */
    public $KeyPeople;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.key_people',
        'app.companies',
        'app.stocks',
        'app.exchanges',
        'app.countries',
        'app.stocks_details'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('KeyPeople') ? [] : ['className' => KeyPeopleTable::class];
        $this->KeyPeople = TableRegistry::get('KeyPeople', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->KeyPeople);

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
