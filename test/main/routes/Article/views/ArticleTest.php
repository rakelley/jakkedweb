<?php
namespace test\main\routes\Article\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\routes\Article\views\Article
 */
class ArticleTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Article';
        $testedClass = '\main\routes\Article\views\Article';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    public function articleProvider()
    {
        return [
            [//minimal set
                [
                    'title' => 'foo article',
                    'date' => '03-14-15',
                    'content' => 'lorem ipsum dolor',
                    'tags' => null,
                    'fullname' => 'jane author',
                    'photo' => null,
                    'profile' => null,
                ],
            ],
            [//with tags
                [
                    'title' => 'foo article',
                    'date' => '03-14-15',
                    'content' => 'lorem ipsum dolor',
                    'tags' => 'bar,baz,bat',
                    'fullname' => 'jane author',
                    'photo' => null,
                    'profile' => null,
                ],
            ],
            [//with author details
                [
                    'title' => 'foo article',
                    'date' => '03-14-15',
                    'content' => 'lorem ipsum dolor',
                    'tags' => null,
                    'fullname' => 'jane author',
                    'photo' => 'foobar.jpg',
                    'profile' => 'foo bar author profile',
                ],
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'article',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider articleProvider
     */
    public function testFetchData($data)
    {
        $id = 1234;
        Utility::setProperties(['parameters' => ['id' => $id]], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('getArticle')
                       ->with($this->identicalTo($id))
                       ->willReturn($data);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($data, 'data', $this->testObj);
    }

    /**
     * @covers ::fetchData
     * @depends testFetchData
     */
    public function testFetchDataNotFound()
    {
        $id = 1234;
        Utility::setProperties(['parameters' => ['id' => $id]], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('getArticle')
                       ->with($this->identicalTo($id))
                       ->willReturn(null);

        $this->setExpectedException('\DomainException');
        $this->testObj->fetchData();
    }


    /**
     * @covers ::fetchMetaData
     * @depends testFetchData
     * @dataProvider articleProvider
     */
    public function testFetchMetaData($data)
    {
        $this->testFetchData($data);

        $this->testObj->fetchMetaData();
        $metadata = $this->readAttribute($this->testObj, 'metaData');
        $this->assertArrayHasKey('title', $metadata);
        $this->assertArrayHasKey('description', $metadata);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @dataProvider articleProvider
     */
    public function testConstructView($data)
    {
        $this->testFetchData($data);

        $this->standardConstructViewTest();
    }
}
