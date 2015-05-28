<?php
namespace test\cms\routes\Testimonialqueue\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Testimonialqueue\views\ApproveForm
 */
class ApproveFormTest extends \test\helpers\cases\FormView
{
    protected $repoMock;


    protected function setUp()
    {
        $builderInterface = '\rakelley\jhframe\interfaces\services\IFormBuilder';
        $repoClass = '\main\repositories\TestimonialQueue';
        $testedClass = '\cms\routes\Testimonialqueue\views\ApproveForm';

        $builderMock = $this->getMock($builderInterface);

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($builderMock, $this->repoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'queue', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $parameters = ['id' => 123];
        Utility::setProperties(['parameters' => $parameters], $this->testObj);

        $testimonial = ['lorem', 'ipsum'];

        $this->repoMock->expects($this->once())
                       ->method('getTestimonial')
                       ->with($this->identicalTo($parameters['id']))
                       ->willReturn($testimonial);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($testimonial, 'data', $this->testObj);
    }
}
