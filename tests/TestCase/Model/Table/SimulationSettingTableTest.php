<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SimulationSettingTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SimulationSettingTable Test Case
 */
class SimulationSettingTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SimulationSettingTable
     */
    public $SimulationSetting;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.simulation_setting',
        'app.brokers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SimulationSetting') ? [] : ['className' => SimulationSettingTable::class];
        $this->SimulationSetting = TableRegistry::get('SimulationSetting', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SimulationSetting);

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
}
