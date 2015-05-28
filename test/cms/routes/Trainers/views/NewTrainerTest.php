<?php
namespace test\cms\routes\Trainers\views;

/**
 * @coversDefaultClass \cms\routes\Trainers\views\NewTrainer
 */
class NewTrainerTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;


    protected $testedClass = '\cms\routes\Trainers\views\NewTrainer';


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
