<?php
namespace test\main\routes\Events;

/**
 * @coversDefaultClass \main\routes\Events\Events
 */
class EventsTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\main\routes\Events\Events';


    /**
     * @covers ::Calendar
     */
    public function testCalendar()
    {
        $this->assertContains('calendar', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'), $this->anything(),
                             $this->isFalse());

        $this->testObj->Calendar();
    }


    /**
     * @covers ::News
     */
    public function testNews()
    {
        $this->assertContains('news', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'));

        $this->testObj->News();
    }


    /**
     * @covers ::Videos
     */
    public function testVideos()
    {
        $this->assertContains('videos', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'), $this->anything(),
                             $this->isFalse());

        $this->testObj->Videos();
    }
}
