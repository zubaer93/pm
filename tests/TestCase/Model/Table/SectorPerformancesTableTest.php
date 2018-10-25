<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SectorPerformancesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SectorPerformancesTable Test Case
 */
class SectorPerformancesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SectorPerformancesTable
     */
    public $SectorPerformances;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sector_performances'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SectorPerformances') ? [] : ['className' => SectorPerformancesTable::class];
        $this->SectorPerformances = TableRegistry::get('SectorPerformances', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SectorPerformances);

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
