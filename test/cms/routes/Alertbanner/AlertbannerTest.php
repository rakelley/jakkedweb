<?php
namespace test\cms\routes\Alertbanner;

/**
 * @coversDefaultClass \cms\routes\Alertbanner\Alertbanner
 */
class AlertbannerTest extends \test\helpers\cases\AuthenticatedRouteController
{
    protected $testedClass = '\cms\routes\Alertbanner\Alertbanner';


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


    /**
     * @covers ::Update
     */
    public function testUpdate()
    {
        $this->assertContains('update', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Update();
    }
}
