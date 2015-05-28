<?php
namespace test\main\routes\Search\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\routes\Search\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\ArticleSearch';
        $testedClass = '\main\routes\Search\views\Index';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    public function articleProvider()
    {
        return [
            [//normal case
                [
                    [
                        'id' => '010',
                        'date' => '03-14-13 00:00:00',
                        'title' => 'foobar title',
                        'author' => 'test mctest',
                        'content' => 'lorem ipsum content',
                    ],
                    [
                        'id' => '020',
                        'date' => '03-14-14 00:00:00',
                        'title' => 'bazbat title',
                        'author' => 'test mctest',
                        'content' => 'lorem ipsum content',
                    ],
                    [
                        'id' => '030',
                        'date' => '03-14-15 00:00:00',
                        'title' => 'other title',
                        'author' => 'test mctest',
                        'content' => 'lorem ipsum content',
                    ],
                ],
                8
            ],
            [//no results case
                null,
                0
            ],
        ];
    }


    public function navEdgeCaseProvider()
    {
        return [
            [1, 3],
            [1, 10],
            [2, 10],
            [5, 10],
            [9, 10],
            [10, 10],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'articles',
                                     $this->testObj);
        $this->assertAttributeNotEmpty('metaRoute', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider articleProvider
     */
    public function testFetchData($articles, $pageCount)
    {
        $perPage = $this->readAttribute($this->testObj, 'perPage');
        $parameters = [
            'query' => 'foobar',
            'page' => 3,
        ];
        $data = [
            'articles' => $articles,
            'pagecount' => $pageCount,
        ];

        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('Search')
                       ->with($this->identicalTo($parameters['query']),
                              $this->identicalTo($parameters['page']),
                              $this->identicalTo($perPage))
                       ->willReturn($data);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($data, 'data', $this->testObj);
    }

    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider articleProvider
     */
    public function testFetchDataNoQuery($articles, $pageCount)
    {
        $perPage = $this->readAttribute($this->testObj, 'perPage');
        $parameters = [
            'page' => 3,
        ];
        $data = [
            'articles' => $articles,
            'pagecount' => $pageCount,
        ];

        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('getPage')
                       ->with($this->identicalTo($parameters['page']),
                              $this->identicalTo($perPage))
                       ->willReturn($articles);
        $this->repoMock->expects($this->once())
                       ->method('getPageCount')
                       ->with($this->identicalTo($perPage))
                       ->willReturn($pageCount);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($data, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @dataProvider articleProvider
     */
    public function testConstructView($articles, $pageCount)
    {
        $parameters = [
            'query' => 'foobar',
            'page' => 3,
        ];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->testFetchData($articles, $pageCount);

        $this->standardConstructViewTest();
    }

    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchDataNoQuery
     * @dataProvider articleProvider
     */
    public function testConstructViewNoQuery($articles, $pageCount)
    {
        $parameters = [
            'page' => 3,
        ];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $this->testFetchDataNoQuery($articles, $pageCount);

        $this->standardConstructViewTest();
    }


    /**
     * @covers ::fillSearchNav
     * @dataProvider navEdgeCaseProvider
     */
    public function testFillSearchNav($page, $count)
    {
        $result = Utility::callMethod($this->testObj, 'fillSearchNav',
                                      [$page, $count]);

        $this->assertTrue(strlen($result) > 1);
    }
}
