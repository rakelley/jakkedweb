<?php
namespace test\cms\repositories;

/**
 * @coversDefaultClass \cms\repositories\FlatPage
 */
class FlatPageTest extends \test\helpers\cases\Base
{
    protected $handlerMock;
    protected $dataMock;


    protected function setUp()
    {
        $handlerClass = '\cms\FlatPageHandler';
        $dataClass = '\main\repositories\PageData';
        $testedClass = '\cms\repositories\FlatPage';

        $this->handlerMock = $this->getMockBuilder($handlerClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->dataMock = $this->getMockBuilder($dataClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->handlerMock, $this->dataMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->handlerMock, 'fileHandler',
                                     $this->testObj);
        $this->assertAttributeEquals($this->dataMock, 'pageData',
                                     $this->testObj);
    }


    /**
     * @covers ::getAll
     * @depends testConstruct
     */
    public function testGetAll()
    {
        $group = $this->readAttribute($this->testObj, 'pageGroup');
        $pages = ['lorem', 'ipsum'];

        $this->dataMock->expects($this->once())
                       ->method('getGroup')
                       ->with($this->identicalTo($group))
                       ->willReturn($pages);

        $this->assertEquals($pages, $this->testObj->getAll());
    }


    /**
     * @covers ::getPage
     * @depends testConstruct
     */
    public function testGetPage()
    {
        $name = 'foobar';
        $content = 'lorem ipsum';
        $data = ['dolor' => 'sit amet'];
        $expected = array_merge($data, ['content' => $content]);

        $this->dataMock->expects($this->once())
                       ->method('getPage')
                       ->with($this->identicalTo($name))
                       ->willReturn($data);

        $this->handlerMock->expects($this->once())
                          ->method('Read')
                          ->with($this->identicalTo($name))
                          ->willReturn($content);

        $this->assertEquals($expected, $this->testObj->getPage($name));
    }

    /**
     * @covers ::getPage
     * @depends testGetPage
     */
    public function testGetPageNotFound()
    {
        $name = 'foobar';
        $data = null;

        $this->dataMock->expects($this->once())
                       ->method('getPage')
                       ->with($this->identicalTo($name))
                       ->willReturn($data);

        $this->handlerMock->expects($this->never())
                          ->method('Read');

        $this->assertEquals(null, $this->testObj->getPage($name));
    }


    /**
     * @covers ::setPage
     * @depends testConstruct
     */
    public function testSetPage()
    {
        $input = [
            'route' => 'foo',
            'content' => 'lorem ipsum',
            'baz' => 'bat'
        ];
        $group = $this->readAttribute($this->testObj, 'pageGroup');
        $expectedData = [
            'route' => 'foo',
            'baz' => 'bat',
            'pagegroup' => $group
        ];

        $this->handlerMock->expects($this->once())
                          ->method('Write')
                          ->with($this->identicalTo($input['route']),
                                 $this->identicalTo($input['content']));

        $this->dataMock->expects($this->once())
                       ->method('setPage')
                       ->with($this->identicalTo($expectedData));

        $this->testObj->setPage($input);
    }


    /**
     * @covers ::addPage
     * @depends testConstruct
     */
    public function testAddPage()
    {
        $input = [
            'route' => 'foo',
            'content' => 'lorem ipsum',
            'baz' => 'bat'
        ];
        $group = $this->readAttribute($this->testObj, 'pageGroup');
        $expectedData = [
            'route' => 'foo',
            'baz' => 'bat',
            'pagegroup' => $group
        ];

        $this->handlerMock->expects($this->once())
                          ->method('Write')
                          ->with($this->identicalTo($input['route']),
                                 $this->identicalTo($input['content']));

        $this->dataMock->expects($this->once())
                       ->method('addPage')
                       ->with($this->identicalTo($expectedData));

        $this->testObj->addPage($input);
    }


    /**
     * @covers ::deletePage
     * @depends testConstruct
     */
    public function testDeletePage()
    {
        $name = 'foobar';

        $this->handlerMock->expects($this->once())
                          ->method('Delete')
                          ->with($this->identicalTo($name));

        $this->dataMock->expects($this->once())
                       ->method('deletePage')
                       ->with($this->identicalTo($name));

        $this->testObj->deletePage($name);
    }
}
