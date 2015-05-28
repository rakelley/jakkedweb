<?php
namespace test\main\routes\Sitemap;

/**
 * @coversDefaultClass \main\routes\Sitemap\Sitemap
 */
class SitemapTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\main\routes\Sitemap\Sitemap';


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
