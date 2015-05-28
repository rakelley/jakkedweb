<?php
namespace test\cms\routes\Trainers\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Trainers\views\Editing
 */
class EditingTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\cms\routes\Trainers\views\Editing';


    /**
     * @covers ::getSubViewList
     */
    public function testGetSubViewList()
    {
        $this->standardGetSubViewListTest();
    }


    /**
     * @covers ::constructView
     * @depends testGetSubViewList
     */
    public function testConstructView()
    {
        $this->mockSubViews();

        $parameters = ['name' => 'foobar'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->standardConstructViewTest();
    }
}
