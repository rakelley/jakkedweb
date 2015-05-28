<?php
namespace test\main\routes\Records\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\routes\Records\views\Powerlifting
 */
class PowerliftingTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider,
        \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\main\routes\Records\views\Powerlifting';


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeNotEmpty('metaRoute', $this->testObj);
    }


    /**
     * @covers ::getSubViewList
     */
    public function testGetSubViewList()
    {
        return $this->standardGetSubViewListTest();
    }

    /**
     * @covers ::getSubViewList
     * @depends testGetSubViewList
     */
    public function testGetSubViewListWithParameters()
    {
        $initialList = $this->testGetSubViewList();

        $parameters = ['lorem', 'ipsum'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $parameterList = $this->standardGetSubViewListTest();
        $this->assertNotEquals($initialList, $parameterList);
    }


    /**
     * @covers ::constructView
     * @depends testGetSubViewList
     * @depends testGetSubViewListWithParameters
     * @dataProvider booleanCaseProvider
     */
    public function testConstructView($withParameters)
    {
        if ($withParameters) {
            $this->testGetSubViewListWithParameters();
        }
        $this->mockSubViews();

        $this->standardConstructViewTest();
    }
}
