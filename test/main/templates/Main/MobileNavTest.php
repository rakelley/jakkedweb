<?php
namespace test\main\templates\Main;

/**
 * @coversDefaultClass \main\templates\Main\MobileNav
 */
class MobileNavTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $navMock;


    protected function setUp()
    {
        $navClass = '\main\repositories\Navigation';
        $testedClass = '\main\templates\Main\MobileNav';

        $this->navMock = $this->getMockBuilder($navClass)
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->testObj = new $testedClass($this->navMock);
    }


    public function navDataProvider()
    {
        return [
            [[
                'parents' => [
                    [
                        'title' => 'foo',
                        'route' => '/foo',
                        'description' => 'any desc',
                    ],
                    [
                        'title' => 'bar',
                        'route' => '/bar/',
                        'description' => 'any desc',
                    ],
                    [
                        'title' => 'baz',
                        'route' => '/baz/baz',
                        'description' => 'any desc',
                    ],
                ],
                'children' => [
                    'foo' => [
                        [
                            'title' => 'lorem',
                            'route' => '/foo/lorem',
                            'description' => 'any desc',
                        ],
                        [
                            'title' => 'ipsum',
                            'route' => '/ipsum',
                            'description' => 'any desc',
                        ],
                    ],
                    'baz' => [
                        [
                            'title' => 'dolor',
                            'route' => '/dolor',
                            'description' => 'any desc',
                        ],
                    ],
                ],
            ]],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->navMock, 'navEntries',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider navDataProvider
     */
    public function testFetchData($data)
    {
        $this->navMock->expects($this->once())
                      ->method('getAll')
                      ->willReturn($data);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($data, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @dataProvider navDataProvider
     */
    public function testConstructView($data)
    {
        $this->navMock->method('getAll')
                      ->willReturn($data);
        $this->testObj->fetchData();

        $this->standardConstructViewTest();
    }
}
