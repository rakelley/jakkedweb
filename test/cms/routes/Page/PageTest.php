<?php
namespace test\cms\routes\Page;

/**
 * @coversDefaultClass \cms\routes\Page\Page
 */
class PageTest extends \test\helpers\cases\RouteController
{

    protected function setUp()
    {
        $testedClass = '\cms\routes\Page\Page';

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
     * @covers ::Editing
     */
    public function testEditing()
    {
        $this->assertContains('editing', $this->routedMethods);

        $parameters = ['name' => 'foobar'];

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
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
     * @covers ::newPage
     */
    public function testnewPage()
    {
        $this->assertContains('newpage', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'));

        $this->testObj->newPage();
    }


    /**
     * @covers ::Add
     */
    public function testAdd()
    {
        $this->assertContains('add', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->Add();
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
}
