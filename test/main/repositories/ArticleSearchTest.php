<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\ArticleSearch
 */
class ArticleSearchTest extends \test\helpers\cases\Base
{
    protected $indexMock;
    protected $cacheMock;
    protected $modelMock;


    protected function setUp()
    {
        $indexClass = '\main\repositories\Article';
        $cacheInterface = '\rakelley\jhframe\interfaces\services\IKeyValCache';
        $modelClass = '\main\models\Article';
        $testedClass = '\main\repositories\ArticleSearch';

        $this->indexMock = $this->getMockBuilder($indexClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->cacheMock = $this->getMock($cacheInterface);

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->testObj = new $testedClass($this->indexMock, $this->cacheMock,
                                          $this->modelMock);
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->indexMock, 'indexRepo',
                                     $this->testObj);
        $this->assertAttributeEquals($this->cacheMock, 'cache',
                                     $this->testObj);
        $this->assertAttributeEquals($this->modelMock, 'articleModel',
                                     $this->testObj);
    }


    /**
     * @covers ::getPage
     * @depends testConstruct
     */
    public function testGetPage()
    {
        $page = 5;
        $perPage = 10;
        $expected = ['foo', 'bar'];

        $this->indexMock->expects($this->once())
                        ->method('getPage')
                        ->with($this->identicalTo($page),
                               $this->identicalTo($perPage))
                        ->willReturn($expected);

        $this->assertEquals(
            $expected,
            $this->testObj->getPage($page, $perPage)
        );
    }


    /**
     * @covers ::getPageCount
     * @depends testConstruct
     */
    public function testGetPageCount()
    {
        $perPage = 10;
        $expected = 5;

        $this->indexMock->expects($this->once())
                        ->method('getPageCount')
                        ->with($this->identicalTo($perPage))
                        ->willReturn($expected);

        $this->assertEquals($expected, $this->testObj->getPageCount($perPage));
    }


    /**
     * @covers ::Search
     * @covers ::<protected>
     * @depends testConstruct
     */
    public function testSearchCachedResult()
    {
        $query = 'foobar';
        $articles = ['foo', 'bar', 'baz'];
        $expected = ['articles' => $articles];

        $this->cacheMock->expects($this->exactly(2))
                        ->method('Read')
                        ->with($this->stringContains($query))
                        ->willReturn($articles);

        $this->assertEquals($expected, $this->testObj->Search($query));
    }

    /**
     * @covers ::Search
     * @covers ::<protected>
     * @depends testSearchCachedResult
     */
    public function testSearchCachedNoResult()
    {
        $query = 'foobar';
        $articles = null;

        $this->cacheMock->expects($this->exactly(2))
                        ->method('Read')
                        ->with($this->stringContains($query))
                        ->willReturn($articles);

        $this->assertEquals($articles, $this->testObj->Search($query));
    }

    /**
     * @covers ::Search
     * @covers ::<protected>
     * @depends testConstruct
     */
    public function testSearchLiveResult()
    {
        $query = 'foobar_bazbat';
        $articles = [
            [//author & content
                'author' => 'foobar',
                'tags' => 'not relevant',
                'title' => 'not relevant',
                'content' => 'other foobar bazbat other other',
            ],
            [//content only, one match
                'author' => 'not relevant',
                'tags' => 'not relevant',
                'title' => 'not relevant',
                'content' => 'other foobar other',
            ],
            [//content only, two matches
                'author' => 'not relevant',
                'tags' => 'not relevant',
                'title' => 'not relevant',
                'content' => 'other foobar other bazbat',
            ],
            [//title & content
                'author' => 'not relevant',
                'tags' => 'not relevant',
                'title' => 'bazbat other',
                'content' => 'other foobar other',
            ],
            [//tags & content
                'author' => 'not relevant',
                'tags' => 'bazbat, other',
                'title' => 'not relevant',
                'content' => 'other other bazbat other',
            ],
        ];
        $sorted = [
            [//author & content
                'author' => 'foobar',
                'tags' => 'not relevant',
                'title' => 'not relevant',
                'content' => 'other foobar bazbat other other',
            ],
            [//tags & content
                'author' => 'not relevant',
                'tags' => 'bazbat, other',
                'title' => 'not relevant',
                'content' => 'other other bazbat other',
            ],
            [//title & content
                'author' => 'not relevant',
                'tags' => 'not relevant',
                'title' => 'bazbat other',
                'content' => 'other foobar other',
            ],
            [//content only, two matches
                'author' => 'not relevant',
                'tags' => 'not relevant',
                'title' => 'not relevant',
                'content' => 'other foobar other bazbat',
            ],
            [//content only, one match
                'author' => 'not relevant',
                'tags' => 'not relevant',
                'title' => 'not relevant',
                'content' => 'other foobar other',
            ],
        ];
        $expected = ['articles' => $sorted];

        $this->cacheMock->expects($this->once())
                        ->method('Read')
                        ->with($this->stringContains($query))
                        ->willReturn(false);
        $this->cacheMock->expects($this->once())
                        ->method('Write')
                        ->with($this->identicalTo($sorted),
                               $this->stringContains($query));

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['query' => $query]));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('results'))
                        ->willReturn($articles);

        $this->assertEquals($expected, $this->testObj->Search($query));
    }

    /**
     * @covers ::Search
     * @covers ::<protected>
     * @depends testConstruct
     */
    public function testSearchLiveNoResult()
    {
        $query = 'foobar';
        $articles = null;

        $this->cacheMock->expects($this->once())
                        ->method('Read')
                        ->with($this->stringContains($query))
                        ->willReturn(false);
        $this->cacheMock->expects($this->once())
                        ->method('Write')
                        ->with($this->identicalTo($articles),
                               $this->stringContains($query));

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['query' => $query]));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('results'))
                        ->willReturn($articles);

        $this->assertEquals($articles, $this->testObj->Search($query));
    }

    /**
     * @covers ::Search
     * @covers ::<protected>
     * @depends testSearchCachedResult
     */
    public function testSearchPaginatedResult()
    {
        $query = 'foobar';
        $page = 1;
        $perPage = 10;
        $articles = array_fill(0, 25, md5(rand()));
        $expected = [
            'articles' => array_slice($articles, 0, 10),
            'pagecount' => 3,
        ];

        $this->cacheMock->expects($this->exactly(2))
                        ->method('Read')
                        ->with($this->stringContains($query))
                        ->willReturn($articles);

        $this->assertEquals(
            $expected,
            $this->testObj->Search($query, $page, $perPage)
        );
    }
}
