<?php
namespace test\cms\routes\Nav\views;

/**
 * @coversDefaultClass \cms\routes\Nav\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews,
        \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Navigation';
        $testedClass = '\cms\routes\Nav\views\Index';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $mockedMethods = [
            'interpolatePlaceholders',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$this->repoMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('interpolatePlaceholders')
                      ->with($this->isType('string'), $this->isType('array'))
                      ->will($this->returnArgument(0));
    }


    public function entryProvider()
    {
        return [
            [[
                'parents' => [
                    [
                        'title' => 'Foo Entry',
                        'route' => '/foo/',
                        'parent' => null,
                    ],
                    [
                        'title' => 'Bar Entry',
                        'route' => '/bar/',
                        'parent' => null,
                    ],
                    [
                        'title' => 'Bat Entry',
                        'route' => '/bat/',
                        'parent' => null,
                    ],
                ],
                'children' => [
                    'Foo Entry' => [
                        [
                            'title' => 'Lorem',
                            'route' => '/foo/lorem',
                            'parent' => 'Foo Entry',
                        ],
                        [
                            'title' => 'Ipsum',
                            'route' => '/foo/ipsum',
                            'parent' => 'Foo Entry',
                        ],
                    ],
                    'Bar Entry' => [
                        [
                            'title' => 'Dolor Entry',
                            'route' => '/bar/dolor',
                            'parent' => 'Bar Entry',
                        ],
                    ],
                ],
            ]]
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'navEntries',
                                     $this->testObj);
    }


    /**
     * @covers ::getSubViewList
     */
    public function testGetSubViewList()
    {
        $this->standardGetSubViewListTest();
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider entryProvider
     */
    public function testFetchData($entries)
    {
        $this->repoMock->expects($this->once())
                       ->method('getAll')
                       ->willReturn($entries);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($entries, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @depends testGetSubViewList
     * @dataProvider entryProvider
     */
    public function testConstructView($entries)
    {
        $this->mockSubViews();
        $this->testFetchData($entries);

        $this->standardConstructViewTest();
    }
}
