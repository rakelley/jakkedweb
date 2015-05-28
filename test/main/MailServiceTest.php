<?php
namespace test\main;

use \rakelley\jhframe\interfaces\services\IMail,
    \Psr\Log\LogLevel,
    \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\MailService
 */
class MailServiceTest extends \test\helpers\cases\Base
{
    protected $ioMock;
    protected $loggerMock;
    protected $userMock;


    protected function setUp()
    {
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $ioInterface = '\rakelley\jhframe\interfaces\services\IIo';
        $loggerInterface = '\rakelley\jhframe\interfaces\services\ILogger';
        $userClass = '\main\repositories\UserAccount';
        $testedClass = '\main\MailService';

        $configMock = $this->getMock($configInterface);
        $configMock->method('Get')
                   ->with($this->identicalTo('APP'),
                          $this->logicalOr(
                            $this->identicalTo('email_admin'),
                            $this->identicalTo('email_main')
                          ))
                   ->willReturn('foobar@example.com');

        $this->ioMock = $this->getMock($ioInterface);

        $this->loggerMock = $this->getMock($loggerInterface);

        $this->userMock = $this->getMockBuilder($userClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $mockedMethods = [
            'getServerProp',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$configMock,
                                                    $this->ioMock,
                                                    $this->loggerMock,
                                                    $this->userMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('getServerProp')
                      ->with($this->identicalTo('REMOTE_ADDR'))
                      ->willReturn('0.0.0.0');
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->ioMock, 'io', $this->testObj);
        $this->assertAttributeEquals($this->loggerMock, 'logger',
                                     $this->testObj);
        $this->assertAttributeEquals($this->userMock, 'users', $this->testObj);
        $this->assertAttributeNotEmpty('accountAdmin', $this->testObj);
        $this->assertAttributeNotEmpty('accountMain', $this->testObj);
    }


    /**
     * @covers ::getValueForConstant
     * @depends testConstruct
     */
    public function testGetValueForConstant()
    {
        $constA = IMail::ACCOUNT_MAIN;
        $this->assertEquals(
            $this->readAttribute($this->testObj, 'accountMain'),
            $this->testObj->getValueForConstant($constA)
        );

        $constB = IMail::ACCOUNT_ADMIN;
        $this->assertEquals(
            $this->readAttribute($this->testObj, 'accountAdmin'),
            $this->testObj->getValueForConstant($constB)
        );

        $constC = IMail::ALL_ADMIN_ACCOUNTS;
        $admins = ['foo', 'bar', 'baz'];
        $this->userMock->expects($this->once())
                       ->method('getAdmins')
                       ->willReturn($admins);
        $this->assertEquals(
            $admins,
            $this->testObj->getValueForConstant($constC)
        );

        $constD = 'undefined value';
        $this->setExpectedException('\DomainException');
        $this->testObj->getValueForConstant($constD);
    }


    /**
     * @covers ::Send
     * @covers ::<protected>
     * @depends testConstruct
     */
    public function testSendSimple()
    {
        $recipient = 'foobar@example.com';
        $title = 'Foobar Email';
        $body = 'Email body lorem ipsum';
        $sender = 'bazbat@example.com';
        $headers = 'example email headers';

        $this->ioMock->expects($this->once())
                     ->method('toMail')
                     ->with($this->identicalTo($recipient),
                            $this->identicalTo($title),
                            $this->identicalTo($body),
                            $this->identicalTo($headers));

        $this->loggerMock->expects($this->once())
                         ->method('Log')
                         ->with($this->identicalTo(LogLevel::INFO),
                                $this->isType('string'));

        $this->testObj->Send($recipient, $title, $body, $sender, $headers);
    }

    /**
     * @covers ::Send
     * @covers ::<protected>
     * @depends testSendSimple
     */
    public function testSendMultiple()
    {
        $recipient = ['foobarA@example.com', 'foobarB@example.com',
                      'foobarC@example.com'];
        $title = 'Foobar Email';
        $body = 'Email body lorem ipsum';
        $sender = 'bazbat@example.com';
        $headers = 'example email headers';

        $this->ioMock->expects($this->exactly(count($recipient)))
                     ->method('toMail')
                     ->with($this->isType('string'),
                            $this->identicalTo($title),
                            $this->identicalTo($body),
                            $this->identicalTo($headers));

        $this->loggerMock->expects($this->once())
                         ->method('Log')
                         ->with($this->identicalTo(LogLevel::INFO),
                                $this->isType('string'));

        $this->testObj->Send($recipient, $title, $body, $sender, $headers);
    }

    /**
     * @covers ::Send
     * @covers ::<protected>
     * @depends testSendSimple
     * @depends testGetValueForConstant
     */
    public function testSendConstRecipient()
    {
        $recipient = IMail::ACCOUNT_MAIN;
        $title = 'Foobar Email';
        $body = 'Email body lorem ipsum';
        $sender = 'bazbat@example.com';
        $headers = 'example email headers';

        $expectedRecipient = $this->testObj->getValueForConstant($recipient);
        $this->ioMock->expects($this->once())
                     ->method('toMail')
                     ->with($this->identicalTo($expectedRecipient),
                            $this->identicalTo($title),
                            $this->identicalTo($body),
                            $this->identicalTo($headers));

        $this->loggerMock->expects($this->once())
                         ->method('Log')
                         ->with($this->identicalTo(LogLevel::INFO),
                                $this->isType('string'));

        $this->testObj->Send($recipient, $title, $body, $sender, $headers);
    }


    /**
     * @covers ::Send
     * @covers ::<protected>
     * @depends testSendSimple
     * @depends testGetValueForConstant
     */
    public function testSendDefaultHeaders()
    {
        $recipient = 'foobar@example.com';
        $title = 'Foobar Email';
        $body = 'Email body lorem ipsum';
        $sender = 'bazbat@example.com';

        $this->ioMock->expects($this->once())
                     ->method('toMail')
                     ->with($this->identicalTo($recipient),
                            $this->identicalTo($title),
                            $this->identicalTo($body),
                            $this->isType('string'));

        $this->loggerMock->expects($this->once())
                         ->method('Log')
                         ->with($this->identicalTo(LogLevel::INFO),
                                $this->isType('string'));

        $this->testObj->Send($recipient, $title, $body, $sender);
    }


    /**
     * @covers ::Send
     * @covers ::<protected>
     * @depends testSendDefaultHeaders
     */
    public function testSendDefaultSenderAndHeaders()
    {
        $recipient = 'foobar@example.com';
        $title = 'Foobar Email';
        $body = 'Email body lorem ipsum';

        $this->ioMock->expects($this->once())
                     ->method('toMail')
                     ->with($this->identicalTo($recipient),
                            $this->identicalTo($title),
                            $this->identicalTo($body),
                            $this->isType('string'));

        $this->loggerMock->expects($this->once())
                         ->method('Log')
                         ->with($this->identicalTo(LogLevel::INFO),
                                $this->isType('string'));

        $this->testObj->Send($recipient, $title, $body);
    }
}
