<?php
namespace test\cms\routes\Recovery;

/**
 * @coversDefaultClass \cms\routes\Recovery\Recovery
 */
class RecoveryTest extends \test\helpers\cases\RouteController
{

    protected function setUp()
    {
        $testedClass = '\cms\routes\Recovery\Recovery';

        $mockedMethods = [
            'standardView',//implemented by parent
            'standardAction',//implemented by parent
            'getArguments',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
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

        $parameters = ['token' => 'abcde12345'];
        $this->testObj->expects($this->once())
                      ->method('getArguments')
                      ->with($this->isType('array'))
                      ->willReturn($parameters);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'),
                             $this->identicalTo($parameters));

        $this->testObj->Index();
    }


    /**
     * @covers ::Recover
     */
    public function testRecover()
    {
        $this->assertContains('recover', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Recover();
    }


    /**
     * @covers ::Initiate
     */
    public function testInitiate()
    {
        $this->assertContains('initiate', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Initiate();
    }
}
