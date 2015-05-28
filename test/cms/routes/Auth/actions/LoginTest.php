<?php
namespace test\cms\routes\Auth\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Auth\actions\Login
 */
class LoginTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $authMock;
    protected $userMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Auth\views\LoginForm';
        $authInterface = '\rakelley\jhframe\interfaces\services\IAuthService';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $userClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Auth\actions\Login';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->authMock = $this->getMock($authInterface);

        $validatorMock = $this->getMock($validatorInterface);

        $this->userMock = $this->getMockBuilder($userClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($viewMock, $this->authMock,
                                          $validatorMock, $this->userMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->authMock, 'authService',
                                     $this->testObj);
        $this->assertAttributeEquals($this->userMock, 'userAccount',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['username' => 'foobar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->authMock->expects($this->once())
                       ->method('logIn')
                       ->with($this->identicalTo($input['username']));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider booleanCaseProvider
     */
    public function testValidateInput($result)
    {
        $input = ['username' => 'foobar', 'password' => 'lorem'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->userMock->expects($this->once())
                       ->method('validatePassword')
                       ->with($this->identicalTo($input['username']),
                              $this->identicalTo($input['password']))
                       ->willReturn($result);

        if (!$result) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
