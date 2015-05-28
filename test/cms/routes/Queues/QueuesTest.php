<?php
namespace test\cms\routes\Queues;

/**
 * @coversDefaultClass \cms\routes\Queues\Queues
 */
class QueuesTest extends \test\helpers\cases\AuthenticatedRouteController
{
    protected $testedClass = '\cms\routes\Queues\Queues';


    /**
     * @covers ::Index
     */
    public function testIndex()
    {
        $this->assertContains('index', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'));

        $this->testObj->Index();
    }
}
