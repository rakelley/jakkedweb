<?php
namespace test\cms\routes\Testimonialqueue;

/**
 * @coversDefaultClass \cms\routes\Testimonialqueue\Testimonialqueue
 */
class TestimonialqueueTest extends \test\helpers\cases\RouteController
{

    protected function setUp()
    {
        $testedClass = '\cms\routes\Testimonialqueue\Testimonialqueue';

        $mockedMethods = [
            'standardView',//implemented by parent
            'standardAction',//implemented by parent
            'routeAuth',//trait implemented
            'getArguments',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();

        $this->createRoutedMethodsList();
    }


    /**
     * @covers ::Item
     */
    public function testItem()
    {
        $this->assertContains('item', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');

        $parameters = ['id' => 1234];
        $this->testObj->expects($this->once())
                      ->method('getArguments')
                      ->with($this->isType('array'))
                      ->willReturn($parameters);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'),
                             $this->identicalTo($parameters));

        $this->testObj->Item();
    }


    /**
     * @covers ::Approve
     */
    public function testApprove()
    {
        $this->assertContains('approve', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');

        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Approve();
    }


    /**
     * @covers ::Reject
     */
    public function testReject()
    {
        $this->assertContains('reject', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');

        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Reject();
    }
}
