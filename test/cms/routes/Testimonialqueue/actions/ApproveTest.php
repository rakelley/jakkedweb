<?php
namespace test\cms\routes\Testimonialqueue\actions;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Testimonialqueue\actions\Approve
 */
class ApproveTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider;

    protected $queueMock;
    protected $repoMock;


    protected function setUp()
    {
        $viewClass = '\cms\routes\Testimonialqueue\views\ApproveForm';
        $validatorInterface =
            '\rakelley\jhframe\interfaces\services\IFormValidator';
        $queueClass = '\main\repositories\TestimonialQueue';
        $repoClass = '\main\repositories\Testimonials';
        $testedClass = '\cms\routes\Testimonialqueue\actions\Approve';

        $viewMock = $this->getMockBuilder($viewClass)
                         ->disableOriginalConstructor()
                         ->getMock();

        $validatorMock = $this->getMock($validatorInterface);

        $this->queueMock = $this->getMockBuilder($queueClass)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($viewMock, $validatorMock,
                                          $this->queueMock, $this->repoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->queueMock, 'queue', $this->testObj);
        $this->assertAttributeEquals($this->repoMock, 'testimonials',
                                     $this->testObj);
    }


    /**
     * @covers ::Proceed
     * @depends testConstruct
     */
    public function testProceed()
    {
        $input = ['id' => 1234, 'lorem' => 'ipsum'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->repoMock->expects($this->once())
                       ->method('addTestimonial')
                       ->with($this->isType('array'));

        $this->queueMock->expects($this->once())
                        ->method('deleteTestimonial')
                        ->with($this->identicalTo($input['id']));

        $this->testObj->Proceed();
    }


    /**
     * @covers ::validateInput
     * @depends testConstruct
     * @dataProvider booleanCaseProvider
     */
    public function testValidateInput($exists)
    {
        $input = ['id' => 1234, 'lorem' => 'ipsum'];
        Utility::setProperties(['input' => $input], $this->testObj);

        $this->queueMock->expects($this->once())
                        ->method('getTestimonial')
                        ->with($this->identicalTo($input['id']))
                        ->willReturn($exists);

        if (!$exists) {
            $this->setExpectedException('\rakelley\jhframe\classes\InputException');
        }
        Utility::callMethod($this->testObj, 'validateInput');
    }
}
