<?php
namespace test\cms\routes\Widgets;

/**
 * @coversDefaultClass \cms\routes\Widgets\Widgets
 */
class WidgetsTest extends \test\helpers\cases\AuthenticatedRouteController
{
    protected $testedClass = '\cms\routes\Widgets\Widgets';


    /**
     * @covers ::Index
     */
    public function testIndex()
    {
        $this->assertContains('index', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'));

        $this->testObj->Index();
    }
}
