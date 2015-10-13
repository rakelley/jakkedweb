<?php
namespace test\cms\routes\Plrecords;

/**
 * @coversDefaultClass \cms\routes\Plrecords\Plrecords
 */
class PlrecordsTest extends \test\helpers\cases\AuthenticatedRouteController
{
    protected $testedClass = '\cms\routes\Plrecords\Plrecords';


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
     * @covers ::addMeet
     */
    public function testAddMeet()
    {
        $this->assertContains('addmeet', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->addMeet();
    }


    /**
     * @covers ::CsvUpdate
     */
    public function testCsvUpdate()
    {
        $this->assertContains('csvupdate', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->CsvUpdate();
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
