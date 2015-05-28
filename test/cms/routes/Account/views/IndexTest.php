<?php
namespace test\cms\routes\Account\views;

/**
 * @coversDefaultClass \cms\routes\Account\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $testedClass = '\cms\routes\Account\views\Index';


    /**
     * @covers ::getSubViewList
     */
    public function testGetSubViewList()
    {
        $this->standardGetSubViewListTest();
    }


    /**
     * @covers ::constructView
     */
    public function testConstructView()
    {
        $this->mockSubViews();

        $this->standardConstructViewTest();
    }
}
