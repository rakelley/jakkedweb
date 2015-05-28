<?php
namespace test\cms\routes\Recovery\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Recovery\actions\Recover
 */
class RecoverTest extends \test\helpers\cases\Base
{
    protected $recoveryMock;
    protected $mailMock;
    protected $userMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Recovery\views\RecoverForm';
        $recoveryClass = '\cms\repositories\Recovery';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $mailInterface = '\rakelley\jhframe\interfaces\services\IMail';
        $userClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Recovery\actions\Recover';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $this->recoveryMock = $this->getMockBuilder($recoveryClass)
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->mailMock = $this->getMock($mailInterface);

        $this->userMock = $this->getMockBuilder($userClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($viewMock, $this->recoveryMock,
                                          $validatorMock, $this->mailMock,
                                          $this->userMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->recoveryMock, 'tokens',
                                     $this->testObj);
        $this->assertAttributeEquals($this->mailMock, 'mail', $this->testObj);
        $this->assertAttributeEquals($this->userMock, 'userAccount',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @covers ::<private>
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['username' => 'foobar@example.com', 'new' => 'password1'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->userMock->expects($this->once())
                       ->method('setPassword')
                       ->with($this->identicalTo($input['username']),
                              $this->identicalTo($input['new']));

        $this->mailMock->expects($this->once())
                       ->method('Send')
                       ->with($this->identicalTo($input['username']),
                              $this->isType('string'),
                              $this->isType('string'));

        $this->recoveryMock->expects($this->once())
                           ->method('Delete')
                           ->with($this->identicalTo($input['username']));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     */
    public function testValidateInput()
    {
        $input = ['username' => 'foobar@example.com', 'token' => 'abcde12345'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->recoveryMock->expects($this->once())
                           ->method('Validate')
                           ->with($this->identicalTo($input['username']),
                                  $this->identicalTo($input['token']));

        Utility::callMethod($this->testObj, 'validateInput');
    }
}
