<?php
namespace test\cms\routes\Flat;

/**
 * @coversDefaultClass \cms\routes\Flat\Flat
 */
class FlatTest extends \test\helpers\cases\RouteController
{

    protected function setUp()
    {
        $testedClass = '\cms\routes\Flat\Flat';

        $mockedMethods = [
            'standardView',//implemented by parent
            'standardAction',//implemented by parent
            'routeAuth',//trait implemented
            'serveFlatView',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();

        $this->createRoutedMethodsList();
    }


    /**
     * @covers ::flatView
     */
    public function testFlatView()
    {
        $this->assertContains('flatView', $this->routedMethods);

        $view = 'foobar';

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('serveFlatView')
                      ->with($this->identicalTo($view));

        $this->testObj->flatView($view);
    }


    /**
     * @covers ::Index
     * @depends testFlatView
     */
    public function testIndex()
    {
        $this->assertContains('index', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('serveFlatView')
                      ->with($this->isType('string'));

        $this->testObj->Index();
    }

    /**
     * @covers ::Index
     * @depends testIndex
     */
    public function testIndexWithAuthFailure()
    {
        $e = new \RuntimeException('expected exc for test', 403);

        $this->testObj->expects($this->once())
                      ->method('routeAuth')
                      ->will($this->throwException($e));
        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'));

        $this->testObj->Index();
    }

    /**
     * @covers ::Index
     * @depends testIndex
     */
    public function testIndexWithUnknownFailure()
    {
        $e = new \RuntimeException('expected exc for test', 500);

        $this->testObj->expects($this->once())
                      ->method('routeAuth')
                      ->will($this->throwException($e));

        $this->setExpectedException('\RuntimeException');
        $this->testObj->Index();
    }
}
