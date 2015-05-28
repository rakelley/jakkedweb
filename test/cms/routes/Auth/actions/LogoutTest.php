<?php
namespace test\cms\routes\Auth\actions;

/**
 * @coversDefaultClass \cms\routes\Auth\actions\Logout
 */
class LogoutTest extends \test\helpers\cases\Base
{
    protected $authMock;


    protected function setUp()
    {
        $authInterface = '\rakelley\jhframe\interfaces\services\IAuthService';
        $testedClass = '\cms\routes\Auth\actions\Logout';

        $this->authMock = $this->getMock($authInterface);

        $this->testObj = new $testedClass($this->authMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->authMock, 'auth', $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $this->authMock->expects($this->once())
                       ->method('logOut');

        $this->testObj->Proceed();
    }
}
