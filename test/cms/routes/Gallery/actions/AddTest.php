<?php
namespace test\cms\routes\Gallery\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Gallery\actions\Add
 */
class AddTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Gallery\views\AddForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $repoClass = '\main\repositories\Gallery';
        $testedClass = '\cms\routes\Gallery\actions\Add';

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
        $this->assertAttributeEquals($this->repoMock, 'galleries',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['lorem', 'ipsum'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('addGallery')
                       ->with($this->identicalTo($input));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider booleanCaseProvider
     */
    public function testValidateInput($exists)
    {
        $input = ['name' => 'foobar', 'indeximage' => 'foobar.jpg'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('galleryExists')
                       ->with($this->identicalTo($input['name']))
                       ->willReturn($exists);

        if ($exists) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        } else {
            $this->repoMock->expects($this->once())
                           ->method('validateImage')
                           ->with($this->identicalTo($input['indeximage']));
        }

        Utility::callMethod($this->testObj, 'validateInput');
    }
}
