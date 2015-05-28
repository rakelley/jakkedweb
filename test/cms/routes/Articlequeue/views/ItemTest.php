<?php
namespace test\cms\routes\Articlequeue\views;

/**
 * @coversDefaultClass \cms\routes\Articlequeue\views\Item
 */
class ItemTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\cms\routes\Articlequeue\views\Item';


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

        $this->standardConstructViewTest();
    }
}
