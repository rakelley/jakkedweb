<?php
namespace test\cms\templates\Cms;

/**
 * @coversDefaultClass \cms\templates\Cms\Nav
 */
class NavTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $navMock;
    protected $authMock;


    protected function setUp()
    {
        $navClass = '\cms\repositories\UserNav';
        $authInterface = '\rakelley\jhframe\interfaces\services\IAuthService';
        $testedClass = '\cms\templates\Cms\Nav';

        $this->navMock = $this->getMockBuilder($navClass)
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->authMock = $this->getMock($authInterface);

        $this->testObj = new $testedClass($this->navMock, $this->authMock);
    }


    public function caseProvider()
    {
        return [
            [//normal case
                'foobar@example.com',
                'Foo Bar',
                [
                    'catA' => [
                        [
                            'title' => 'lorem ipsum 1',
                            'path' => 'foo/1',
                        ],
                        [
                            'title' => 'lorem ipsum 2',
                            'path' => 'foo/2',
                        ],
                    ],
                    'catB' => [
                        [
                            'title' => 'lorem ipsum 3',
                            'path' => 'bar/3',
                        ],
                    ],
                    'catC' => [
                        [
                            'title' => 'lorem ipsum 4',
                            'path' => 'baz/4',
                        ],
                    ],
                ],
            ],
            [//no user case
                null,
                null,
                null,
            ],
            [//no entries case
                'foobar@example.com',
                'Foo Bar',
                null,
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->navMock, 'userNav', $this->testObj);
        $this->assertAttributeEquals($this->authMock, 'auth', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider caseProvider
     */
    public function testFetchData($username, $fullname, $nav)
    {
        $this->authMock->expects($this->at(0))
                       ->method('getUser')
                       ->with($this->identicalTo('username'))
                       ->willReturn($username);

        if ($username) {
            $this->authMock->expects($this->at(1))
                           ->method('getUser')
                           ->with($this->identicalTo('fullname'))
                           ->willReturn($fullname);
            $this->navMock->expects($this->once())
                          ->method('getNav')
                          ->with($this->identicalTo($username))
                          ->willReturn($nav);
            $expected = ['name' => $fullname, 'nav' => $nav];
        } else {
            $expected = null;
        }

        $this->testObj->fetchData();
        $this->assertAttributeEquals($expected, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @dataProvider caseProvider
     */
    public function testConstructView($username, $fullname, $nav)
    {
        $this->testFetchData($username, $fullname, $nav);

        $this->standardConstructViewTest();
    }
}