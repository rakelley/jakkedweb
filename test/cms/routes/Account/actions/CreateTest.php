<?php
namespace test\cms\routes\Account\actions;

use \rakelley\jhframe\interfaces\services\IMail,
    \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Account\actions\Create
 */
class CreateTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $mailMock;
    protected $accountMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Account\views\CreateForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $mailInterface = '\rakelley\jhframe\interfaces\services\IMail';
        $accountClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Account\actions\Create';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->mailMock = $this->getMock($mailInterface);

        $this->accountMock = $this->getMockBuilder($accountClass)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $mockedMethods = [
            'validateSpamCheck',//trait implemented
            'getConfig',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$viewMock, $validatorMock,
                                                    $this->mailMock,
                                                    $this->accountMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
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
     * @covers ::<private>
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['lorem' => 'ipsum', 'username' => 'foobar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->accountMock->expects($this->once())
                          ->method('addUser')
                          ->with($this->identicalTo($input));

        $this->mailMock->expects($this->at(0))
                       ->method('Send')
                       ->with($this->identicalTo($input['username']),
                              $this->isType('string'),
                              $this->isType('string'));
        $this->mailMock->expects($this->at(1))
                       ->method('Send')
                       ->with($this->identicalTo(IMail::ALL_ADMIN_ACCOUNTS),
                              $this->isType('string'),
                              $this->isType('string'));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider booleanCaseProvider
     */
    public function testValidateInput($userExists)
    {
        $input = ['username' => 'foobar@example.com', 'spamcheck' => 'lorem'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->testObj->expects($this->once())
                      ->method('validateSpamCheck')
                      ->with($this->identicalTo($input['spamcheck']));

        $this->accountMock->expects($this->once())
                          ->method('userExists')
                          ->with($this->identicalTo($input['username']))
                          ->willReturn($userExists);

        if ($userExists) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
