<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\Navigation
 */
class NavigationTest extends \test\helpers\cases\Base
{
    protected $modelMock;


    protected function setUp()
    {
        $modelClass = '\main\models\Nav';
        $testedClass = '\main\repositories\Navigation';

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->testObj = new $testedClass($this->modelMock);
    }


    public function entryProvider()
    {
        $entries = [
            ['title' => 'foo', 'parent' => null],
            ['title' => 'bar', 'parent' => 'foo'],
            ['title' => 'baz', 'parent' => null],
            ['title' => 'bat', 'parent' => 'foo'],
            ['title' => 'lorem', 'parent' => 'baz'],
            ['title' => 'ipsum', 'parent' => null],
        ];

        return [
            ['foo', ['title' => 'foo', 'parent' => null], $entries, true],
            ['bar', ['title' => 'bar', 'parent' => 'foo'], $entries, false],
            ['dolor', null, $entries, false],
        ];
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->modelMock, 'navModel',
                                     $this->testObj);
    }


    /**
     * @covers ::getAll
     * @depends testConstruct
     */
    public function testGetAll()
    {
        $entries = [
            ['title' => 'foo', 'parent' => null],
            ['title' => 'bar', 'parent' => 'foo'],
            ['title' => 'baz', 'parent' => null],
            ['title' => 'bat', 'parent' => 'foo'],
            ['title' => 'lorem', 'parent' => 'baz'],
            ['title' => 'ipsum', 'parent' => null],
        ];
        $expected = [
            'parents' => [
                ['title' => 'foo', 'parent' => null],
                ['title' => 'baz', 'parent' => null],
                ['title' => 'ipsum', 'parent' => null],
            ],
            'children' => [
                'foo' => [
                    ['title' => 'bar', 'parent' => 'foo'],
                    ['title' => 'bat', 'parent' => 'foo'],
                ],
                'baz' => [
                    ['title' => 'lorem', 'parent' => 'baz'],
                ],
            ],
        ];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('entries'))
                        ->willReturn($entries);

        $this->assertEquals($expected, $this->testObj->getAll());
    }


    /**
     * @covers ::getAll
     * @depends testGetAll
     */
    public function testGetAllEmpty()
    {
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('entries'))
                        ->willReturn([]);

        $this->assertEquals(null, $this->testObj->getAll());
    }


    /**
     * @covers ::getEntry
     * @depends testConstruct
     */
    public function testGetEntry()
    {
        $route = 'foobar';
        $entry = ['an', 'array'];

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['route' => $route]));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('entry'))
                        ->willReturn($entry);

        $this->assertEquals($entry, $this->testObj->getEntry($route));
    }


    /**
     * @covers ::setEntry
     * @depends testConstruct
     */
    public function testSetEntry()
    {
        $input = ['an', 'array'];

        $this->modelMock->expects($this->once())
                        ->method('__set')
                        ->with($this->identicalTo('entry'),
                               $this->identicalTo($input));

        $this->testObj->setEntry($input);
    }


    /**
     * @covers ::addEntry
     * @depends testConstruct
     */
    public function testAddEntry()
    {
        $input = ['an', 'array'];

        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->identicalTo($input));

        $this->testObj->addEntry($input);
    }


    /**
     * @covers ::deleteEntry
     * @depends testConstruct
     */
    public function testDeleteEntry()
    {
        $route = 'foobar';

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['route' => $route]));
        $this->modelMock->expects($this->once())
                        ->method('__unset')
                        ->with($this->identicalTo('entry'));

        $this->testObj->deleteEntry($route);
    }


    /**
     * @covers ::entryExists
     * @depends testGetEntry
     */
    public function testEntryExists()
    {
        $route = 'foobar';
        $entry = ['an', 'array'];
        $expected = (bool) $entry;

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['route' => $route]));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('entry'))
                        ->willReturn($entry);

        $this->assertEquals($expected, $this->testObj->entryExists($route));
    }


    /**
     * @covers ::entryHasChildren
     * @depends testGetEntry
     * @depends testGetAll
     * @dataProvider entryProvider
     */
    public function testEntryHasChildren($route, $entry, $entries, $expected)
    {
        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['route' => $route]));
        $this->modelMock->expects($this->at(1))
                        ->method('__get')
                        ->with($this->identicalTo('entry'))
                        ->willReturn($entry);
        if ($entry) {
            $this->modelMock->expects($this->at(2))
                            ->method('__get')
                            ->with($this->identicalTo('entries'))
                            ->willReturn($entries);
        }

        $this->assertEquals(
            $expected,
            $this->testObj->entryHasChildren($route)
        );
    }


    /**
     * @covers ::getParentList
     * @depends testConstruct
     */
    public function testGetParentList()
    {
        $list = ['an', 'array'];

        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('parentList'))
                        ->willReturn($list);

        $this->assertEquals($list, $this->testObj->getParentList());
    }
}
