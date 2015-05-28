<?php
namespace test\cms\routes\Newarticle;

/**
 * @coversDefaultClass \cms\routes\Newarticle\Newarticle
 */
class NewarticleTest extends \test\helpers\cases\AuthenticatedRouteController
{
    protected $testedClass = '\cms\routes\Newarticle\Newarticle';


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
     * @covers ::Write
     */
    public function testWrite()
    {
        $this->assertContains('write', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Write();
    }
}
