<?php
namespace test\main\routes\Records;

/**
 * @coversDefaultClass \main\routes\Records\Records
 */
class RecordsTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\main\routes\Records\Records';


    protected function setUp()
    {
        $mockedMethods = [
            'standardView',//implemented by parent
            'standardAction',//implemented by parent
            'getArguments',//implemented by trait
        ];
        $this->testObj = $this->getMockBuilder($this->testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();

        $this->createRoutedMethodsList();
    }


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
     * @covers ::Powerlifting
     */
    public function testPowerlifting()
    {
        $parameters = ['lorem', 'ipsum'];

        $this->assertContains('powerlifting', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('getArguments')
                      ->with($this->isType('array'))
                      ->willReturn($parameters);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'),
                             $this->identicalTo($parameters));

        $this->testObj->Powerlifting();
    }


    /**
     * @covers ::plQuery
     */
    public function testPlQuery()
    {
        $parameters = ['lorem', 'ipsum'];

        $this->assertContains('plquery', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('getArguments')
                      ->with($this->isType('array'))
                      ->willReturn($parameters);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'),
                             $this->identicalTo($parameters));

        $this->testObj->plQuery();
    }
}
