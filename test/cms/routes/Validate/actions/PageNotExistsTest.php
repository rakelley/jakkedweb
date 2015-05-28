<?php
namespace test\cms\routes\Validate\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * @coversDefaultClass \cms\routes\Validate\actions\PageNotExists
 */
class PageNotExistsTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\DoubleBooleanCaseProvider;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\cms\repositories\FlatPage';
        $testedClass = '\cms\routes\Validate\actions\PageNotExists';

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
        $this->assertAttributeEquals($this->repoMock, 'page', $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     * @dataProvider doubleBooleanCaseProvider
     */
    public function testProceed($inputPasses, $pageExists)
    {
        $e = new InputException('exception for test');

        if ($inputPasses) {
            $input = ['route' => 'foobar'];
            $this->testObj->expects($this->once())
                          ->method('getInput')
                          ->with($this->isType('array'),
                                 $this->identicalTo('post'))
                          ->willReturn($input);
            $this->repoMock->expects($this->once())
                           ->method('getPage')
                           ->with($this->identicalTo($input['route']))
                           ->willReturn($pageExists);
        } else {
            $this->testObj->expects($this->once())
                          ->method('getInput')
                          ->with($this->isType('array'),
                                 $this->identicalTo('post'))
                          ->will($this->throwException($e));
        }

        $result = $this->testObj->Proceed();
        if ($inputPasses && !$pageExists) {
            $this->assertTrue($result);
            $this->assertAttributeEmpty('error', $this->testObj);
        } else {
            $this->assertFalse($result);
        }
    }
}
