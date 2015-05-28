<?php
namespace test\main\routes\Contactus\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\routes\Contactus\actions\Contact
 */
class ContactTest extends \test\helpers\cases\Base
{
    protected $mailMock;


    protected function setUp()
    {
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $mailInterface = '\rakelley\jhframe\interfaces\services\IMail';
        $viewClass = '\main\routes\Contactus\views\ContactForm';
        $testedClass = '\main\routes\Contactus\actions\Contact';

        $validatorMock = $this->getMock($validatorInterface);

        $this->mailMock = $this->getMock($mailInterface);

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $mockedMethods = [
            'validateBotcheckField',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$validatorMock,
                                                    $this->mailMock,
                                                    $viewMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->mailMock, 'mail', $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = [
            'name' => 'foo bar',
            'email' => 'foo@bar.com',
            'textarea' => 'lorem ipsum'
        ];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->mailMock->expects($this->once())
                       ->method('Send')
                       ->with($this->anything(),
                              $this->isType('string'),
                              $this->isType('string'),
                              $this->identicalTo($input['email']));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     */
    public function testValidateInput()
    {
        $this->testObj->expects($this->once())
                      ->method('validateBotcheckField');

        Utility::callMethod($this->testObj, 'validateInput');
    }
}
