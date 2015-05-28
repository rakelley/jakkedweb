<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\Article
 */
class ArticleTest extends \test\helpers\cases\Base
{
    protected $filterMock;
    protected $modelMock;
    protected $userMock;


    protected function setUp()
    {
        $filterInterface = '\rakelley\jhframe\interfaces\services\IFilter';
        $modelClass = '\main\models\Article';
        $userClass = '\main\repositories\UserAccount';
        $testedClass = '\main\repositories\Article';

        $this->filterMock = $this->getMock($filterInterface);

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->userMock = $this->getMockBuilder($userClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->userMock, $this->filterMock,
                                          $this->modelMock);
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->userMock, 'userAccount',
                                     $this->testObj);
        $this->assertAttributeEquals($this->filterMock, 'filter',
                                     $this->testObj);
        $this->assertAttributeEquals($this->modelMock, 'articleModel',
                                     $this->testObj);
    }


    /**
     * @covers ::getAuthors
     * @depends testConstruct
     */
    public function testGetAuthors()
    {
        $authors = ['lorem', 'ipsum', 'array'];
        $this->userMock->expects($this->once())
                        ->method('getAuthors')
                        ->willReturn($authors);

        $this->assertEquals($authors, $this->testObj->getAuthors());
    }


    /**
     * @covers ::getAll
     * @depends testConstruct
     */
    public function testGetAll()
    {
        $articles = ['lorem', 'ipsum', 'array'];
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('articles'))
                        ->willReturn($articles);

        $this->assertEquals($articles, $this->testObj->getAll());
    }


    /**
     * @covers ::getArticle
     * @depends testConstruct
     */
    public function testGetArticle()
    {
        $id = 99;
        $article = ['author' => 'foobar', 'other' => 'property'];
        $photo = 'foobar.jpg';
        $expected = array_merge($article, ['photo' => $photo]);

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['id' => $id]));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('article'))
                        ->willReturn($article);

        $this->userMock->expects($this->once())
                       ->method('getPhoto')
                       ->with($this->identicalTo($article['author']))
                       ->willReturn($photo);

        $this->assertEquals($expected, $this->testObj->getArticle($id));
    }

    /**
     * @covers ::getArticle
     * @depends testGetArticle
     */
    public function testGetArticleNotFound()
    {
        $id = 99;

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['id' => $id]));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('article'))
                        ->willReturn(null);

        $this->userMock->expects($this->never())
                       ->method('getPhoto');

        $this->assertEquals(null, $this->testObj->getArticle($id));
    }


    /**
     * @covers ::setArticle
     * @depends testConstruct
     */
    public function testSetArticle()
    {
        $input = ['foo' => 'bar'];
        $this->modelMock->expects($this->once())
                        ->method('__set')
                        ->with($this->identicalTo('article'),
                               $this->identicalTo($input));

        $this->testObj->setArticle($input);
    }


    /**
     * @covers ::addArticle
     * @depends testConstruct
     */
    public function testAddArticle()
    {
        $input = ['foo' => 'bar'];
        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->identicalTo($input));

        $this->testObj->addArticle($input);
    }


    /**
     * @covers ::deleteArticle
     * @depends testConstruct
     */
    public function testDeleteArticle()
    {
        $id = 99;

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['id' => $id]));
        $this->modelMock->expects($this->once())
                        ->method('__unset')
                        ->with($this->identicalTo('article'));

        $this->testObj->deleteArticle($id);
    }


    /**
     * @covers ::getPage
     * @depends testConstruct
     * @dataProvider pageArgProvider
     */
    public function testGetPage($page, $perPage)
    {
        $parameters = [
            'pointer' => ($page - 1) * $perPage,
            'results' => $perPage,
        ];
        $articles = ['foo', 'bar', 'baz'];

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo($parameters));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('articles'))
                        ->willReturn($articles);

        $this->assertEquals(
            $articles,
            $this->testObj->getPage($page, $perPage)
        );
    }

    public function pageArgProvider()
    {
        return [
            [3, 20],
            [1, 10],
            [5, 5],
        ];
    }


    /**
     * @covers ::getPageCount
     * @depends testConstruct
     * @dataProvider pageCountProvider
     */
    public function testGetPageCount($perPage, $count, $expected)
    {
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('count'))
                        ->willReturn($count);

        $this->assertEquals($expected, $this->testObj->getPageCount($perPage));
    }

    public function pageCountProvider()
    {
        return [
            [10, 100, 10],
            [10, 47, 5],
            [15, 46, 4],
        ];
    }


    /**
     * @covers ::filterNews
     * @depends testConstruct
     */
    public function testFilterNewsSimple()
    {
        $content = 'foobar';

        $this->filterMock->expects($this->once())
                         ->method('tidyText')
                         ->with($this->identicalTo($content))
                         ->will($this->returnArgument(0));

        $this->assertEquals($content, $this->testObj->filterNews($content));
    }

    /**
     * @covers ::filterNews
     * @depends testFilterNewsSimple
     */
    public function testFilterNewsBadTags()
    {
        $content = 'foobar<script src="xss.js" /><img src="foobar.jpg />';
        $expected = 'foobar';

        $this->filterMock->expects($this->once())
                         ->method('tidyText')
                         ->with($this->identicalTo($expected))
                         ->will($this->returnArgument(0));

        $this->assertEquals($expected, $this->testObj->filterNews($content));
    }

    /**
     * @covers ::filterNews
     * @depends testFilterNewsSimple
     */
    public function testFilterNewsTooLong()
    {
        //random string of length 672
        $content = implode('', array_map(
            function($i) {
                return md5(rand());
            },
            range(0,20)
        ));
        $short = substr($content, 0, 500);
        $expected = $short . '...';

        $this->filterMock->expects($this->once())
                         ->method('tidyText')
                         ->with($this->identicalTo($short))
                         ->will($this->returnArgument(0));

        $this->assertEquals($expected, $this->testObj->filterNews($content));
    }


    /**
     * @covers ::revertAuthor
     * @depends testConstruct
     */
    public function testRevertAuthor()
    {
        $author = 'foobar';

        $this->modelMock->expects($this->once())
                        ->method('revertAuthor')
                        ->with($this->identicalTo($author));

        $this->testObj->revertAuthor($author);
    }
}
