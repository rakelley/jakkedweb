<?php
namespace test\main\routes\Rss;

/**
 * @coversDefaultClass \main\routes\Rss\Rss
 */
class RssTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\main\routes\Rss\Rss';


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
