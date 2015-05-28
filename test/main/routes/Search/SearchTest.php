<?php
namespace test\main\routes\Search;

/**
 * @coversDefaultClass \main\routes\Search\Search
 */
class SearchTest extends \test\helpers\cases\RouteController
{
    protected $testedClass = '\main\routes\Search\Search';


    protected function setUp()
    {
        $mockedMethods = [
            'standardView',//implemented by parent
            'standardAction',//implemented by parent
            'getArguments',//trait implemented
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

        $parameters = ['lorem', 'ipsum'];

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
}
