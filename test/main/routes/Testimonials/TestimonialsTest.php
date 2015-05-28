<?php
namespace test\main\routes\Testimonials;

/**
 * @coversDefaultClass \main\routes\Testimonials\Testimonials
 */
class TestimonialsTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\main\routes\Testimonials\Testimonials';


    /**
     * @covers ::Index
     */
    public function testIndex()
    {
        $this->assertContains('index', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'),
                             $this->anything(),
                             $this->identicalTo(false));

        $this->testObj->Index();
    }


    /**
     * @covers ::Add
     */
    public function testAdd()
    {
        $this->assertContains('add', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Add();
    }
}
