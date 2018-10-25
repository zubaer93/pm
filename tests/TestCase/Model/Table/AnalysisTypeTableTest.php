<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnalysisTypeTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnalysisTypeTable Test Case
 */
class AnalysisTypeTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AnalysisTypeTable
     */
    public $AnalysisType;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.analysis_type'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AnalysisType') ? [] : ['className' => AnalysisTypeTable::class];
        $this->AnalysisType = TableRegistry::get('AnalysisType', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AnalysisType);

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
