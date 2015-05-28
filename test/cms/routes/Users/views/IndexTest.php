<?php
namespace test\cms\routes\Users\views;

/**
 * @coversDefaultClass \cms\routes\Users\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Users\views\Index';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'users',
                                     $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $users = [
            [
                'username' => 'foobar@example.com',
                'fullname' => 'Foo Bar',
                'lastlogin' => '2015-03-14 03:14:15',
            ],
            [
                'username' => 'barbaz@example.com',
                'fullname' => 'Bar Baz',
                'lastlogin' => '2014-03-14 03:14:15',
            ],
            [
                'username' => 'bazbat@example.com',
                'fullname' => 'Baz Bat',
                'lastlogin' => '2013-03-14 03:14:15',
            ],
        ];

        $this->repoMock->expects($this->once())
                       ->method('getAll')
                       ->willReturn($users);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($users, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     */
    public function testConstructView()
    {
        $this->testFetchData();

        $this->standardConstructViewTest();
    }
}
