<?php
namespace test\cms\repositories;

/**
 * @coversDefaultClass \cms\repositories\SessionData
 */
class SessionDataTest extends \test\helpers\cases\Base
{
    protected $modelMock;
    protected $cacheMock;
    protected $userMock;


    protected function setUp()
    {
        $modelClass = '\cms\models\SessionData';
        $cacheInterface = '\rakelley\jhframe\interfaces\services\IKeyValCache';
        $userClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\repositories\SessionData';

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->cacheMock = $this->getMock($cacheInterface);

        $this->userMock = $this->getMockBuilder($userClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $mockedMethods = [
            'getServerProp',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$this->modelMock,
                                                    $this->cacheMock,
                                                    $this->userMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->modelMock, 'dataModel',
                                     $this->testObj);
        $this->assertAttributeEquals($this->cacheMock, 'cache', $this->testObj);
        $this->assertAttributeEquals($this->userMock, 'userAccount',
                                     $this->testObj);
    }


    /**
     * @covers ::destroySession
     * @covers ::getCacheKey
     * @depends testConstruct
     */
    public function testDestroySession()
    {
        $stringKey = 'foobar';
        $arrayKey = ['foo', 'bar'];

        $this->modelMock->expects($this->at(0))
                        ->method('Remove')
                        ->with($this->identicalTo([$stringKey]));
        $this->modelMock->expects($this->at(1))
                        ->method('Remove')
                        ->with($this->identicalTo($arrayKey));
        $this->cacheMock->expects($this->exactly(2))
                        ->method('Purge')
                        ->with($this->isType('array'));

        $this->testObj->destroySession($stringKey);
        $this->testObj->destroySession($arrayKey);
    }


    /**
     * @covers ::getSession
     * @covers ::Validate
     * @covers ::getCacheKey
     * @depends testConstruct
     */
    public function testGetSessionCached()
    {
        $id = '1234';
        $ip = '127.0.0.1';
        $session = [
            'id' => $id,
            'username' => 'foobar@example.com',
            'expiry' => date(\DateTime::RFC3339, time() + 1000),
            'ip' => $ip,
            'fullname' => 'Foo Bar',
            'permissions' => ['lorem', 'ipsum'],
        ];

        $this->cacheMock->expects($this->once())
                        ->method('Read')
                        ->with($this->stringContains($id))
                        ->willReturn($session);

        $this->testObj->method('getServerProp')
                      ->with($this->identicalTo('REMOTE_ADDR'))
                      ->willReturn($ip);

        $this->assertEquals($session, $this->testObj->getSession($id));
    }

    /**
     * @covers ::getSession
     * @covers ::Validate
     * @depends testConstruct
     */
    public function testGetSessionLive()
    {
        $id = '1234';
        $ip = '127.0.0.1';
        $model = [
            'id' => $id,
            'username' => 'foobar@example.com',
            'expiry' => date(\DateTime::RFC3339, time() + 1000),
            'ip' => $ip,
        ];
        $fullname = 'Foo Bar';
        $permissions = ['lorem', 'ipsum'];
        $expected = array_merge(
            $model,
            ['fullname' => $fullname, 'permissions' => $permissions]
        );

        $this->cacheMock->expects($this->once())
                        ->method('Read')
                        ->with($this->stringContains($id))
                        ->willReturn(false);
        $this->cacheMock->expects($this->once())
                        ->method('Write')
                        ->with($this->identicalTo($expected),
                               $this->stringContains($id));

        $this->modelMock->expects($this->once())
                        ->method('Get')
                        ->with($this->identicalTo($id))
                        ->willReturn($model);

        $this->userMock->expects($this->once())
                       ->method('getFullname')
                       ->with($this->identicalTo($model['username']))
                       ->willReturn($fullname);
        $this->userMock->expects($this->once())
                       ->method('getPermissions')
                       ->with($this->identicalTo($model['username']))
                       ->willReturn($permissions);

        $this->testObj->method('getServerProp')
                      ->with($this->identicalTo('REMOTE_ADDR'))
                      ->willReturn($ip);

        $this->assertEquals($expected, $this->testObj->getSession($id));
    }

    /**
     * @covers ::getSession
     * @depends testGetSessionLive
     */
    public function testGetSessionNotFound()
    {
        $id = '1234';

        $this->cacheMock->expects($this->once())
                        ->method('Read')
                        ->with($this->stringContains($id))
                        ->willReturn(false);

        $this->modelMock->expects($this->once())
                        ->method('Get')
                        ->with($this->identicalTo($id))
                        ->willReturn(null);

        $this->assertEquals(null, $this->testObj->getSession($id));
    }

    /**
     * @covers ::getSession
     * @covers ::Validate
     * @depends testGetSessionCached
     * @depends testDestroySession
     */
    public function testGetSessionExpired()
    {
        $id = '1234';
        $ip = '127.0.0.1';
        $session = [
            'id' => $id,
            'username' => 'foobar@example.com',
            'expiry' => date(\DateTime::RFC3339, time() - 1000),
            'ip' => $ip,
            'fullname' => 'Foo Bar',
            'permissions' => ['lorem', 'ipsum'],
        ];

        $this->cacheMock->expects($this->once())
                        ->method('Read')
                        ->with($this->stringContains($id))
                        ->willReturn($session);

        $this->testObj->method('getServerProp')
                      ->with($this->identicalTo('REMOTE_ADDR'))
                      ->willReturn($ip);

        $this->assertEquals(null, $this->testObj->getSession($id));
    }

    /**
     * @covers ::getSession
     * @covers ::Validate
     * @depends testGetSessionCached
     * @depends testDestroySession
     */
    public function testGetSessionInvalidRemote()
    {
        $id = '1234';
        $ip = '127.0.0.1';
        $session = [
            'id' => $id,
            'username' => 'foobar@example.com',
            'expiry' => date(\DateTime::RFC3339, time() + 1000),
            'ip' => '192.168.1.1',
            'fullname' => 'Foo Bar',
            'permissions' => ['lorem', 'ipsum'],
        ];

        $this->cacheMock->expects($this->once())
                        ->method('Read')
                        ->with($this->stringContains($id))
                        ->willReturn($session);

        $this->testObj->method('getServerProp')
                      ->with($this->identicalTo('REMOTE_ADDR'))
                      ->willReturn($ip);

        $this->assertEquals(null, $this->testObj->getSession($id));
    }


    /**
     * @covers ::createSession
     * @depends testDestroySession
     */
    public function testCreateSession()
    {
        $id = '1234';
        $username = 'foobar@example.com';

        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->isType('array'));

        $this->userMock->expects($this->once())
                       ->method('setLastLogin')
                       ->with($this->identicalTo($username),
                              $this->isType('string'));

        $this->testObj->createSession($id, $username);
    }
}
