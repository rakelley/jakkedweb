<?php
namespace test\main\routes\Testimonials\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\routes\Testimonials\actions\Add
 */
class AddTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\TestimonialQueue';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $viewClass = '\main\routes\Testimonials\views\AddForm';
        $testedClass = '\main\routes\Testimonials\actions\Add';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $mockedMethods = [
            'validateBotcheckField',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$validatorMock,
                                                    $viewMock,
                                                    $this->repoMock])
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'queue', $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['textarea' => 'lorem ipsum', 'name' => 'foo bar'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('addTestimonial')
                       ->with($this->isType('array'));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     */
    public function testValidateInput()
    {
        $this->testObj->expects($this->once())
                      ->method('validateBotcheckField');

        Utility::callMethod($this->testObj, 'validateInput');
    }
}
