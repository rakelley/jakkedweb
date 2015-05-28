<?php
namespace test\cms\routes\Trainers\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Trainers\actions\SetPhoto
 */
class SetPhotoTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Trainers\views\PhotoForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $repoClass = '\main\repositories\Trainers';
        $testedClass = '\cms\routes\Trainers\actions\SetPhoto';

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
        $input = ['name' => 'foobar', 'photo' => ['uploaded', 'photo']];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('setPhoto')
                       ->with($this->identicalTo($input['name']),
                              $this->identicalTo($input['photo']));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     */
    public function testValidateInput()
    {
        $input = ['name' => 'foobar', 'photo' => ['uploaded', 'photo']];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('validatePhoto')
                       ->with($this->identicalTo($input['photo']));

        Utility::callMethod($this->testObj, 'validateInput');
    }
}
