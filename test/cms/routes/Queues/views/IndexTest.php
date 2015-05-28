<?php
namespace test\cms\routes\Queues\views;

/**
 * @coversDefaultClass \cms\routes\Queues\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\cms\routes\Queues\views\Index';


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
