<?php
namespace test\cms\repositories;

/**
 * @coversDefaultClass \cms\repositories\UserNav
 */
class UserNavTest extends \test\helpers\cases\Base
{
    protected $modelMock;


    protected function setUp()
    {
        $modelClass = '\cms\models\CmsNav';
        $testedClass = '\cms\repositories\UserNav';

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->testObj = new $testedClass($this->modelMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->modelMock, 'nav', $this->testObj);
    }


    /**
     * @covers ::getNav
     * @depends testConstruct
     */
    public function testGetNavNoResults()
    {
        $entries = [];
        $username = 'foobar';

        $this->modelMock->expects($this->once())
                        ->method('getNav')
                        ->with($this->identicalTo($username))
                        ->willReturn($entries);

        $this->assertEquals(null, $this->testObj->getNav($username));
    }


    /**
     * @covers ::getNav
     * @depends testConstruct
     */
    public function testGetNav()
    {
        $entries = [
            [
                'category' => 'catA',
                'permission' => 'permA',
                'title' => 'lorem ipsum 1',
                'path' => '1',
            ],
            [
                'category' => 'catC',
                'permission' => 'permB',
                'title' => 'lorem ipsum 4',
                'path' => '4',
            ],
            [
                'category' => 'catA',
                'permission' => 'permC',
                'title' => 'lorem ipsum 3',
                'path' => '3',
            ],
            [
                'category' => 'catC',
                'permission' => 'permA',
                'title' => 'lorem ipsum 2',
                'path' => '2',
            ],
            [
                'category' => 'catB',
                'permission' => 'permD',
                'title' => 'lorem ipsum 5',
                'path' => '5',
            ],
        ];
        $expected = [
            'catA' => [
                [
                    'title' => $entries[0]['title'],
                    'path' => $entries[0]['path']
                ],
                [
                    'title' => $entries[2]['title'],
                    'path' => $entries[2]['path']
                ],
            ],
            'catB' => [
                [
                    'title' => $entries[4]['title'],
                    'path' => $entries[4]['path']
                ],
            ],
            'catC' => [
                [
                    'title' => $entries[1]['title'],
                    'path' => $entries[1]['path']
                ],
                [
                    'title' => $entries[3]['title'],
                    'path' => $entries[3]['path']
                ],
            ],
        ];
        $username = 'foobar';

        $this->modelMock->expects($this->once())
                        ->method('getNav')
                        ->with($this->identicalTo($username))
                        ->willReturn($entries);

        $this->assertEquals($expected, $this->testObj->getNav($username));
    }
}
