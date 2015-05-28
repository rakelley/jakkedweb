<?php
namespace test\cms\routes\Article;

/**
 * @coversDefaultClass \cms\routes\Article\Article
 */
class ArticleTest extends \test\helpers\cases\RouteController
{

    protected function setUp()
    {
        $testedClass = '\cms\routes\Article\Article';

        $mockedMethods = [
            'standardView',//implemented by parent
            'standardAction',//implemented by parent
            'routeAuth',//trait implemented
            'getArguments',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();

        $this->createRoutedMethodsList();
    }


    /**
     * @covers ::Editing
     */
    public function testEditing()
    {
        $this->assertContains('editing', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');

        $parameters = ['id' => 1234];
        $this->testObj->expects($this->once())
                      ->method('getArguments')
                      ->with($this->isType('array'))
                      ->willReturn($parameters);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'),
                             $this->identicalTo($parameters));

        $this->testObj->Editing();
    }


    /**
     * @covers ::Index
     */
    public function testIndex()
    {
        $this->assertContains('index', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');

        $parameters = ['page' => 2];
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
     * @covers ::Delete
     */
    public function testDelete()
    {
        $this->assertContains('delete', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');

        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Delete();
    }


    /**
     * @covers ::Edit
     */
    public function testEdit()
    {
        $this->assertContains('edit', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');

        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Edit();
    }
}
