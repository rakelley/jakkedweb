<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\PageData
 */
class PageDataTest extends \test\helpers\cases\Base
{
    protected $modelMock;


    protected function setUp()
    {
        $modelClass = '\main\models\Page';
        $testedClass = '\main\repositories\PageData';

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->testObj = new $testedClass($this->modelMock);
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->modelMock, 'pageModel',
                                     $this->testObj);
    }


    /**
     * @covers ::getAll
     * @depends testConstruct
     */
    public function testGetAll()
    {
        $pages = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('pages'))
                        ->willReturn($pages);

        $this->assertEquals($pages, $this->testObj->getAll());
    }


    /**
     * @covers ::getGroup
     * @depends testConstruct
     */
    public function testGetGroup()
    {
        $group = 'foobar';
        $pages = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('getGroup')
                        ->with($this->identicalTo($group))
                        ->willReturn($pages);

        $this->assertEquals($pages, $this->testObj->getGroup($group));
    }


    /**
     * @covers ::getPage
     * @depends testConstruct
     */
    public function testGetPage()
    {
        $route = 'foobar';
        $page = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['route' => $route]));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('page'))
                        ->willReturn($page);

        $this->assertEquals($page, $this->testObj->getPage($route));
    }


    /**
     * @covers ::setPage
     * @depends testConstruct
     */
    public function testSetPage()
    {
        $page = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('__set')
                        ->with($this->identicalTo('page'),
                               $this->identicalTo($page));

        $this->testObj->setPage($page);
    }


    /**
     * @covers ::addPage
     * @depends testConstruct
     */
    public function testAddPage()
    {
        $page = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->identicalTo($page));

        $this->testObj->addPage($page);
    }


    /**
     * @covers ::deletePage
     * @depends testConstruct
     */
    public function testdeletePage()
    {
        $route = 'foobar';

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['route' => $route]));
        $this->modelMock->expects($this->once())
                        ->method('__unset')
                        ->with($this->identicalTo('page'));

        $this->testObj->deletePage($route);
    }
}
