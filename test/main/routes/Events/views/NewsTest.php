<?php
namespace test\main\routes\Events\views;

/**
 * @coversDefaultClass \main\routes\Events\views\News
 */
class NewsTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Article';
        $testedClass = '\main\routes\Events\views\News';

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
        $this->assertAttributeEquals($this->repoMock, 'articles',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $articles = [
            [
                'id' => 10,
                'date' => '2015-03-15',
                'title' => 'lorem ipsum',
                'author' => 'foobar mccrackens',
                'content' => 'lorem ipsum dolor',
            ],
            [
                'id' => 20,
                'date' => '2015-03-15',
                'title' => 'lorem ipsum',
                'author' => 'foobar mccrackens',
                'content' => 'lorem ipsum dolor',
            ],
            [
                'id' => 30,
                'date' => '2015-03-15',
                'title' => 'lorem ipsum',
                'author' => 'foobar mccrackens',
                'content' => 'lorem ipsum dolor',
            ],
        ];

        $this->repoMock->expects($this->once())
                       ->method('getPage')
                       ->with($this->identicalTo(1),
                              $this->isType('int'))
                       ->willReturn($articles);
        $this->repoMock->expects($this->exactly(count($articles)))
                       ->method('filterNews')
                       ->with($this->isType('string'))
                       ->will($this->returnArgument(0));

        $this->testObj->fetchData();
        $this->assertAttributeEquals($articles, 'data', $this->testObj);
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
