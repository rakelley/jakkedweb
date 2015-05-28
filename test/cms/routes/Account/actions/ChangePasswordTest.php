<?php
namespace test\cms\routes\Account\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Account\actions\ChangePassword
 */
class ChangePasswordTest extends \test\helpers\cases\Base
{
    protected $mailMock;
    protected $accountMock;
    protected $username = 'foobar';


    protected function setUp()
    {
        $viewClass = '\cms\routes\Account\views\PasswordForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $mailInterface = '\rakelley\jhframe\interfaces\services\IMail';
        $accountClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Account\actions\ChangePassword';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->mailMock = $this->getMock($mailInterface);

        $this->accountMock = $this->getMockBuilder($accountClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->testObj = new $testedClass($viewMock, $validatorMock,
                                          $this->mailMock, $this->accountMock);
        Utility::setProperties(['parameters' => ['username' => $this->username]],
                               $this->testObj);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->mailMock, 'mail', $this->testObj);
        $this->assertAttributeEquals($this->accountMock, 'userAccount',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @covers ::sendMail
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['new' => 'password1234'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->accountMock->expects($this->once())
                          ->method('setPassword')
                          ->with($this->identicalTo($this->username),
                                 $this->identicalTo($input['new']));
        $this->mailMock->expects($this->once())
                       ->method('Send')
                       ->with($this->identicalTo($this->username),
                              $this->isType('string'),
                              $this->isType('string'));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     */
    public function testValidateInput()
    {
        $input = ['old' => 'password1234'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->accountMock->expects($this->once())
                          ->method('validatePassword')
                          ->with($this->identicalTo($this->username),
                                 $this->identicalTo($input['old']))
                          ->willReturn(true);

        Utility::callMethod($this->testObj, 'validateInput');
    }

    /**
     * @covers ::validateInput
     * @depends testConstruct
     */
    public function testValidateInputFailure()
    {
        $input = ['old' => 'password1234'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->accountMock->expects($this->once())
                          ->method('validatePassword')
                          ->with($this->identicalTo($this->username),
                                 $this->identicalTo($input['old']))
                          ->willReturn(false);

        $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
