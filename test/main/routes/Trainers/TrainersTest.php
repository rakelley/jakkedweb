<?php
namespace test\main\routes\Trainers;

/**
 * @coversDefaultClass \main\routes\Trainers\Trainers
 */
class TrainersTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\main\routes\Trainers\Trainers';


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
