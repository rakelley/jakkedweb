<?php
namespace test\main\routes\Contactus;

/**
 * @coversDefaultClass \main\routes\Contactus\Contactus
 */
class ContactusTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\main\routes\Contactus\Contactus';


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


    /**
     * @covers ::Contact
     */
    public function testContact()
    {
        $this->assertContains('contact', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Contact();
    }
}
