<?php
namespace test\cms\routes\Recovery\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Recovery\actions\Initiate
 */
class InitiateTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $recoveryMock;
    protected $mailMock;
    protected $userMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Recovery\views\InitiateForm';
        $recoveryClass = '\cms\repositories\Recovery';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $mailInterface = '\rakelley\jhframe\interfaces\services\IMail';
        $userClass = '\main\repositories\UserAccount';
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $testedClass = '\cms\routes\Recovery\actions\Initiate';

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

        $configMock = $this->getMock($configInterface);
        $configMock->method('Get')
                   ->with($this->identicalTo('APP'),
                          $this->identicalTo('base_path'))
                   ->willReturn('https://cms.example.com');

        $mockedMethods = [
            'getConfig',//trait implemented
            'validateSpamCheck',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('getConfig')
                      ->willReturn($configMock);
        Utility::callConstructor($this->testObj, [$viewMock,
                                                  $this->recoveryMock,
                                                  $validatorMock,
                                                  $this->mailMock,
                                                  $this->userMock]);
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
        $this->assertAttributeNotEmpty('basePath', $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @covers ::<private>
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['username' => 'foobar@example.com'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $token = 'abcde1234';

        $this->recoveryMock->expects($this->once())
                           ->method('Create')
                           ->with($this->identicalTo($input['username']))
                           ->willReturn($token);

        $this->mailMock->expects($this->once())
                       ->method('Send')
                       ->with($this->identicalTo($input['username']),
                              $this->isType('string'),
                              $this->stringContains($token));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::getResult
     */
    public function testGetResult()
    {
        $result = $this->testObj->getResult();
        $this->assertTrue(strlen($result) > 1);
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider booleanCaseProvider
     */
    public function testValidateInput($exists)
    {
        $input = ['username' => 'foobar@example.com', 'spamcheck' => 'lorem'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->testObj->expects($this->once())
                      ->method('validateSpamCheck')
                      ->with($this->identicalTo($input['spamcheck']));

        $this->userMock->expects($this->once())
                       ->method('userExists')
                       ->with($this->identicalTo($input['username']))
                       ->willReturn($exists);

        if (!$exists) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
