<?php
namespace test\main\routes\Testimonials\views;

/**
 * @coversDefaultClass \main\routes\Testimonials\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Testimonials';
        $testedClass = '\main\routes\Testimonials\views\Index';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'testimonials',
                                     $this->testObj);
        $this->assertAttributeNotEmpty('metaRoute', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $count = $this->readAttribute($this->testObj, 'testCount');
        $testimonials = [
            'lorem ipsum',
            'dolor',
            'sit amet',
        ];

        $this->repoMock->expects($this->once())
                       ->method('getRandom')
                       ->with($this->identicalTo($count))
                       ->willReturn($testimonials);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($testimonials, 'data', $this->testObj);
    }


    /**
     * @covers ::getSubViewList
     */
    public function testGetSubViewList()
    {
        $this->standardGetSubViewListTest();
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @depends testGetSubViewList
     */
    public function testConstructView()
    {
        $this->mockSubViews();
        $this->testFetchData();

        $this->standardConstructViewTest();
    }
}
