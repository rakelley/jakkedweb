<?php
namespace test\cms\repositories;

use \test\helpers\PHPUnitUtil as Utility,
    \rakelley\jhframe\interfaces\services\IAuthService;

/**
 * @coversDefaultClass \cms\repositories\UserAuth
 */
class UserAuthTest extends \test\helpers\cases\Base
{
    protected $dataMock;
    protected $sessionMock;


    protected function setUp()
    {
        $dataClass = '\cms\repositories\SessionData';
        $sessionInterface =
            '\rakelley\jhframe\interfaces\services\ISessionAbstractor';
        $testedClass = '\cms\repositories\UserAuth';

        $this->dataMock = $this->getMockBuilder($dataClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->sessionMock = $this->getMock($sessionInterface);

        $this->testObj = new $testedClass($this->dataMock, $this->sessionMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->dataMock, 'sessionData',
                                     $this->testObj);
        $this->assertAttributeEquals($this->sessionMock, 'sessionAbstractor',
                                     $this->testObj);
    }


    /**
     * @covers ::getUser
     * @depends testConstruct
     */
    public function testGetUser()
    {
        $id = '1234';
        $data = ['foo' => 'bar', 'baz' => 'bat'];

        $this->sessionMock->expects($this->once())
                          ->method('getId')
                          ->willReturn($id);

        $this->dataMock->expects($this->once())
                       ->method('getSession')
                       ->with($this->identicalTo($id))
                       ->willReturn($data);

        $this->assertEquals($data, $this->testObj->getUser());
        $this->assertAttributeEquals($data, 'currentUser', $this->testObj);
    }

    /**
     * @covers ::getUser
     * @depends testGetUser
     */
    public function testGetUserExisting()
    {
        $data = ['foo' => 'bar', 'baz' => 'bat'];
        Utility::setProperties(['currentUser' => $data], $this->testObj);

        $this->sessionMock->expects($this->never())
                          ->method('getId');

        $this->dataMock->expects($this->never())
                       ->method('getSession');

        $this->assertEquals($data, $this->testObj->getUser());
    }

    /**
     * @covers ::getUser
     * @depends testGetUser
     */
    public function testGetUserWithArg()
    {
        $id = '1234';
        $data = ['foo' => 'bar', 'baz' => 'bat'];
        $validArg = 'foo';
        $invalidArg = 'other';

        $this->sessionMock->method('getId')
                          ->willReturn($id);

        $this->dataMock->method('getSession')
                       ->with($this->identicalTo($id))
                       ->willReturn($data);

        $this->assertEquals(
            $data[$validArg],
            $this->testObj->getUser($validArg)
        );
        $this->assertEquals(null, $this->testObj->getUser($invalidArg));
    }


    /**
     * @covers ::logIn
     * @depends testConstruct
     */
    public function testLogIn()
    {
        $id = '1234';
        $username = 'foobar@example.com';

        $this->sessionMock->expects($this->once())
                          ->method('newSession');
        $this->sessionMock->expects($this->once())
                          ->method('getId')
                          ->willReturn($id);

        $this->dataMock->expects($this->once())
                       ->method('createSession')
                       ->with($this->identicalTo($id),
                              $this->identicalTo($username));

        $this->testObj->logIn($username);
    }


    /**
     * @covers ::logOut
     * @depends testConstruct
     */
    public function testLogOut()
    {
        $id = '1234';
        $user = ['lorem', 'ipsum'];
        Utility::setProperties(['currentUser' => $user], $this->testObj);

        $this->sessionMock->expects($this->once())
                          ->method('getId')
                          ->willReturn($id);
        $this->sessionMock->expects($this->once())
                          ->method('closeSession');

        $this->dataMock->expects($this->once())
                       ->method('destroySession')
                       ->with($this->identicalTo($id));

        $this->testObj->logOut();
        $this->assertAttributeEmpty('currentUser', $this->testObj);
    }


    /**
     * @covers ::checkPermission
     * @depends testGetUser
     * @dataProvider permissionCaseProvider
     */
    public function testCheckPermission($data, $permission, $expected)
    {
        $this->dataMock->method('getSession')
                       ->willReturn($data);

        $this->assertEquals(
            $expected,
            $this->testObj->checkPermission($permission)
        );
    }

    public function permissionCaseProvider()
    {
        return [
            [//user has permission
                ['permissions' => ['foo', 'bar']], 'foo', true
            ],
            [//user doesn't have permission
                ['permissions' => ['foo', 'bar']], 'baz', false
            ],
            [//universal user permission
                ['anything'], IAuthService::PERMISSION_ALLUSERS, true
            ],
            [//no user
                null, IAuthService::PERMISSION_ALLUSERS, false
            ],
        ];
    }
}
