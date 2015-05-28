<?php
namespace test\cms\repositories;

/**
 * @coversDefaultClass \cms\repositories\Recovery
 */
class RecoveryTest extends \test\helpers\cases\Base
{
    protected $modelMock;
    protected $cryptoMock;


    protected function setUp()
    {
        $modelClass = '\cms\models\AccountRecovery';
        $cryptoInterface = '\rakelley\jhframe\interfaces\services\ICrypto';
        $testedClass = '\cms\repositories\Recovery';

        $this->modelMock = $this->getMockBuilder($modelClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->cryptoMock = $this->getMock($cryptoInterface);

        $this->testObj = new $testedClass($this->modelMock, $this->cryptoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->modelMock, 'tokens',
                                     $this->testObj);
        $this->assertAttributeEquals($this->cryptoMock, 'crypto',
                                     $this->testObj);
    }


    /**
     * @covers ::getEntry
     * @depends testConstruct
     */
    public function testGetEntry()
    {
        $username = 'foobar';
        $entry = ['lorem', 'ipsum'];

        $this->modelMock->expects($this->once())
                        ->method('setParameters')
                        ->with($this->identicalTo(['username' => $username]));
        $this->modelMock->expects($this->once())
                        ->method('__get')
                        ->with($this->identicalTo('entry'))
                        ->willReturn($entry);

        $this->assertEquals($entry, $this->testObj->getEntry($username));
    }


    /**
     * @covers ::Validate
     * @depends testConstruct
     */
    public function testValidate()
    {
        $username = 'foobar';
        $token = 'abcde';
        $entry = [
            'username' => $username,
            'token' => '$examplehash',
            'expiration' => date(\DateTime::RFC3339, time() + 10000),
        ];

        $this->modelMock->method('__get')
                        ->with($this->identicalTo('entry'))
                        ->willReturn($entry);

        $this->cryptoMock->expects($this->once())
                         ->method('compareHash')
                         ->with($this->identicalTo($token),
                                $this->identicalTo($entry['token']))
                         ->willReturn(true);

        $this->testObj->Validate($username, $token);
    }

    /**
     * @covers ::Validate
     * @depends testValidate
     */
    public function testValidateNoEntry()
    {
        $username = 'foobar';
        $token = 'abcde';
        $entry = null;

        $this->modelMock->method('__get')
                        ->with($this->identicalTo('entry'))
                        ->willReturn($entry);

        $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        $this->testObj->Validate($username, $token);
    }

    /**
     * @covers ::Validate
     * @depends testValidate
     */
    public function testValidateTokenDoesntMatch()
    {
        $username = 'foobar';
        $token = 'abcde';
        $entry = [
            'username' => $username,
            'token' => '$examplehash',
            'expiration' => date(\DateTime::RFC3339, time() + 10000),
        ];

        $this->modelMock->method('__get')
                        ->with($this->identicalTo('entry'))
                        ->willReturn($entry);

        $this->cryptoMock->expects($this->once())
                         ->method('compareHash')
                         ->with($this->identicalTo($token),
                                $this->identicalTo($entry['token']))
                         ->willReturn(false);

        $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        $this->testObj->Validate($username, $token);
    }

    /**
     * @covers ::Validate
     * @depends testValidate
     */
    public function testValidateTokenExpired()
    {
        $username = 'foobar';
        $token = 'abcde';
        $entry = [
            'username' => $username,
            'token' => '$examplehash',
            'expiration' => date(\DateTime::RFC3339, time() - 10000),
        ];

        $this->modelMock->method('__get')
                        ->with($this->identicalTo('entry'))
                        ->willReturn($entry);

        $this->cryptoMock->expects($this->once())
                         ->method('compareHash')
                         ->with($this->identicalTo($token),
                                $this->identicalTo($entry['token']))
                         ->willReturn(true);

        $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        $this->testObj->Validate($username, $token);
    }


    /**
     * @covers ::Create
     * @depends testConstruct
     */
    public function testCreate()
    {
        $username = 'foobar';
        $token = 'lorem';
        $hash = '$ipsum';

        $this->cryptoMock->expects($this->once())
                         ->method('createRandomString')
                         ->willReturn($token);
        $this->cryptoMock->expects($this->once())
                         ->method('hashString')
                         ->with($this->identicalTo($token))
                         ->willReturn($hash);

        $this->modelMock->expects($this->once())
                        ->method('Add')
                        ->with($this->isType('array'));

        $this->assertEquals($token, $this->testObj->Create($username));
    }


    /**
     * @covers ::Delete
     * @depends testConstruct
     */
    public function testDelete()
    {
        $username = 'foobar';

        $this->modelMock->expects($this->once())
                        ->method('Delete')
                        ->with($this->identicalTo($username));

        $this->testObj->Delete($username);
    }
}
