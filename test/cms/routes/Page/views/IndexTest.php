<?php
namespace test\cms\routes\Page\views;

/**
 * @coversDefaultClass \cms\routes\Page\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\cms\repositories\FlatPage';
        $testedClass = '\cms\routes\Page\views\Index';

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
        $this->assertAttributeEquals($this->repoMock, 'pages', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $pages = ['lorem', 'ipsum', 'dolor'];

        $this->repoMock->expects($this->once())
                       ->method('getAll')
                       ->willReturn($pages);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($pages, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     */
    public function testConstructView()
    {
        $this->testFetchData();

        $this->standardConstructViewTest();
    }
}
