<?php
namespace test\cms\routes\Gallery\views;

/**
 * @coversDefaultClass \cms\routes\Gallery\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Gallery';
        $testedClass = '\cms\routes\Gallery\views\Index';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $mockedMethods = [
            'interpolatePlaceholders',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$this->repoMock])
                              ->setMethods($mockedMethods)
                              ->getMock();

        $this->testObj->method('interpolatePlaceholders')
                      ->with($this->isType('string'), $this->isType('array'))
                      ->will($this->returnArgument(0));
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'galleries',
                                     $this->testObj);
    }


    /**
     * @covers ::getSubViewList
     */
    public function testGetSubViewList()
    {
        $this->standardGetSubViewListTest();
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $galleries = ['foo', 'bar', 'baz'];

        $this->repoMock->expects($this->once())
                       ->method('getAll')
                       ->willReturn($galleries);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($galleries, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::fillGallery
     * @depends testGetSubViewList
     * @depends testFetchData
     */
    public function testConstructView()
    {
        $this->mockSubViews();
        $this->testFetchData();

        $this->standardConstructViewTest();
    }
}
