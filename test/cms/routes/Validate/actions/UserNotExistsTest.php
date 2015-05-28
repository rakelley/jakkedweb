<?php
namespace test\cms\routes\Validate\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * @coversDefaultClass \cms\routes\Validate\actions\UserNotExists
 */
class UserNotExistsTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\DoubleBooleanCaseProvider;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\UserAccount';
        $testedClass = '\cms\routes\Validate\actions\UserNotExists';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $mockedMethods = [
            'getInput',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$this->repoMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'userAccount',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     * @dataProvider doubleBooleanCaseProvider
     */
    public function testProceed($inputPasses, $userExists)
    {
        $e = new InputException('exception for test');

        if ($inputPasses) {
            $input = ['username' => 'foobar'];
            $this->testObj->expects($this->once())
                          ->method('getInput')
                          ->with($this->isType('array'),
                                 $this->identicalTo('post'))
                          ->willReturn($input);
            $this->repoMock->expects($this->once())
                           ->method('userExists')
                           ->with($this->identicalTo($input['username']))
                           ->willReturn($userExists);
        } else {
            $this->testObj->expects($this->once())
                          ->method('getInput')
                          ->with($this->isType('array'),
                                 $this->identicalTo('post'))
                          ->will($this->throwException($e));
        }

        $result = $this->testObj->Proceed();
        if ($inputPasses && !$userExists) {
            $this->assertTrue($result);
            $this->assertAttributeEmpty('error', $this->testObj);
        } else {
            $this->assertFalse($result);
        }
    }
}
