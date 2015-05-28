<?php
namespace test\cms\routes\Quotes\views;

/**
 * @coversDefaultClass \cms\routes\Quotes\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\cms\routes\Quotes\views\Index';


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
