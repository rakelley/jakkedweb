<?php
namespace test\cms\routes\Article\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Article\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Article';
        $testedClass = '\cms\routes\Article\views\Index';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    public function articleCaseProvider()
    {
        $articles = [
            [
                'id' => 123,
                'title' => 'Foobar Article',
                'date' => '2015-03-14 00:00:00',
                'author' => 'lorem ipsum',
            ],
            [
                'id' => 234,
                'title' => 'Barbaz Article',
                'date' => '2014-03-14 00:00:00',
                'author' => 'lorem ipsum',
            ],
            [
                'id' => 345,
                'title' => 'Bazbat Article',
                'date' => '2013-03-14 00:00:00',
                'author' => 'lorem ipsum',
            ],
        ];
        return [
            [//first page of articles
                $articles,
                1,
                2,
            ],
            [//middle page of articles
                $articles,
                2,
                4,
            ],
            [//last page of articles
                $articles,
                3,
                3,
            ],
            [//no articles
                null,
                1,
                0,
            ],
        ];
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
     * @dataProvider articleCaseProvider
     */
    public function testFetchData($articles, $page, $pageCount)
    {
        $parameters = ['page' => $page];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $expected = ['articles' => $articles, 'pagecount' => $pageCount];

        $this->repoMock->expects($this->once())
                       ->method('getPage')
                       ->with($this->identicalTo($parameters['page']),
                              $this->isType('int'))
                       ->willReturn($articles);
        $this->repoMock->expects($this->once())
                       ->method('getPageCount')
                       ->with($this->isType('int'))
                       ->willReturn($pageCount);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($expected, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @dataProvider articleCaseProvider
     */
    public function testConstructView($articles, $page, $pageCount)
    {
        $this->testFetchData($articles, $page, $pageCount);

        $this->standardConstructViewTest();
    }
}
