<?php
namespace test\cms\routes\Nav\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Nav\actions\Delete
 */
class DeleteTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Nav\views\DeleteForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $repoClass = '\main\repositories\Navigation';
        $testedClass = '\cms\routes\Nav\actions\Delete';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($viewMock, $validatorMock,
                                          $this->repoMock);
    }


    public function validationCaseProvider()
    {
        return [
            [true, false],//exists no children, passes
            [true, true],//exists with children, fails
            [false, false],//doesn't exist, fails
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'navEntries',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['route' => 'foobar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('deleteEntry')
                       ->with($this->identicalTo($input['route']));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider validationCaseProvider
     */
    public function testValidateInput($exists, $hasChildren)
    {
        $input = ['route' => 'foobar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('entryExists')
                       ->with($this->identicalTo($input['route']))
                       ->willReturn($exists);
        if ($exists) {
            $this->repoMock->expects($this->once())
                           ->method('entryHasChildren')
                           ->with($this->identicalTo($input['route']))
                           ->willReturn($hasChildren);
        }

        if (!$exists || $hasChildren) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
