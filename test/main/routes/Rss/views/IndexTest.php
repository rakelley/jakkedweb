<?php
namespace test\main\routes\Rss\views;

/**
 * @coversDefaultClass \main\routes\Rss\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $filterMock;
    protected $repoMock;


    protected function setUp()
    {
        $filterInterface = '\rakelley\jhframe\interfaces\services\IFilter';
        $repoClass = '\main\repositories\Article';
        $testedClass = '\main\routes\Rss\views\Index';

        $this->filterMock = $this->getMock($filterInterface);

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->filterMock, $this->repoMock);
    }


    public function articleProvider()
    {
        return [
            [
                [
                    [
                        'id' => '010',
                        'title' => 'foo bar article',
                        'content' => 'lorem ipsum content',
                        'date' => '03-14-13 00:00:00',
                    ],
                    [
                        'id' => '020',
                        'title' => 'baz bat article',
                        'content' => 'lorem ipsum content',
                        'date' => '03-14-14 00:00:00',
                    ],
                    [
                        'id' => '030',
                        'title' => 'other article',
                        'content' => 'lorem ipsum content',
                        'date' => '03-14-15 00:00:00',
                    ],
                ],
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->filterMock, 'filter',
                                     $this->testObj);
        $this->assertAttributeEquals($this->repoMock, 'articles',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider articleProvider
     */
    public function testFetchData($articles)
    {
        $this->repoMock->expects($this->once())
                       ->method('getAll')
                       ->willReturn($articles);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($articles, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @dataProvider articleProvider
     */
    public function testConstructView($articles)
    {
        $this->testFetchData($articles);

        $this->filterMock->expects($this->exactly(count($articles)))
                         ->method('tidyText')
                         ->with($this->isType('string'))
                         ->will($this->returnArgument(0));
        $this->filterMock->expects($this->exactly(count($articles)))
                         ->method('encodeHtml')
                         ->with($this->isType('string'))
                         ->will($this->returnArgument(0));

        $this->standardConstructViewTest();
    }
}
