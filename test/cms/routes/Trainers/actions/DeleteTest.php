<?php
namespace test\cms\routes\Trainers\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Trainers\actions\Delete
 */
class DeleteTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Trainers\views\DeleteForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $repoClass = '\main\repositories\Trainers';
        $testedClass = '\cms\routes\Trainers\actions\Delete';

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


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'trainers',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['name' => 'foobar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('deleteTrainer')
                       ->with($this->identicalTo($input['name']));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider booleanCaseProvider
     */
    public function testValidateInput($exists)
    {
        $input = ['name' => 'foobar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('getTrainer')
                       ->with($this->identicalTo($input['name']))
                       ->willReturn($exists);

        if (!$exists) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
