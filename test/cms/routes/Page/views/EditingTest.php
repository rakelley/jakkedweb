<?php
namespace test\cms\routes\Page\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Page\views\Editing
 */
class EditingTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\cms\routes\Page\views\Editing';


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
        $parameters = ['name' => 'foobar'];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->mockSubViews();

        $this->standardConstructViewTest();
    }
}
