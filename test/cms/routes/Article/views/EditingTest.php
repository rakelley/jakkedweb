<?php
namespace test\cms\routes\Article\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Article\views\Editing
 */
class EditingTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\cms\routes\Article\views\Editing';


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
        $parameters = ['id' => 1234];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->mockSubViews();

        $this->standardConstructViewTest();
    }
}
