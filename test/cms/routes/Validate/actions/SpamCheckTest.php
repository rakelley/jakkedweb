<?php
namespace test\cms\routes\Validate\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * @coversDefaultClass \cms\routes\Validate\actions\SpamCheck
 */
class SpamCheckTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\DoubleBooleanCaseProvider;


    protected function setUp()
    {
        $testedClass = '\cms\routes\Validate\actions\SpamCheck';

        $mockedMethods = [
            'validateSpamCheck',//trait implemented
            'getInput',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @covers ::Proceed
     * @dataProvider doubleBooleanCaseProvider
     */
    public function testProceed($inputPasses, $validatePasses)
    {
        $e = new InputException('exception for test');

        if ($inputPasses) {
            $input = ['spamcheck' => 'lorem'];
            $this->testObj->expects($this->once())
                          ->method('getInput')
                          ->with($this->isType('array'),
                                 $this->identicalTo('post'))
                          ->willReturn($input);
            $this->testObj->expects($this->once())
                          ->method('validateSpamCheck')
                          ->with($this->identicalTo($input['spamcheck']),
                                 $this->identicalTo(true))
                          ->willReturn($validatePasses);
        } else {
            $this->testObj->expects($this->once())
                          ->method('getInput')
                          ->with($this->isType('array'),
                                 $this->identicalTo('post'))
                          ->will($this->throwException($e));
        }

        $result = $this->testObj->Proceed();
        if ($inputPasses && $validatePasses) {
            $this->assertTrue($result);
            $this->assertAttributeEmpty('error', $this->testObj);
        } else {
            $this->assertFalse($result);
            $this->assertAttributeNotEmpty('error', $this->testObj);
        }
    }
}
