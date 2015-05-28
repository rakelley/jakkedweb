<?php
namespace test\cms\routes\Users;

/**
 * @coversDefaultClass \cms\routes\Users\Users
 */
class UsersTest extends \test\helpers\cases\RouteController
{

    protected function setUp()
    {
        $testedClass = '\cms\routes\Users\Users';

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
     * @covers ::Edit
     */
    public function testEdit()
    {
        $this->assertContains('edit', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');

        $parameters = ['username' => 'foobar'];
        $this->testObj->expects($this->once())
                      ->method('getArguments')
                      ->with($this->isType('array'))
                      ->willReturn($parameters);

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'),
                             $this->identicalTo($parameters));

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


    /**
     * @covers ::setRoles
     */
    public function testSetRoles()
    {
        $this->assertContains('setRoles', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('routeAuth');
        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->setRoles();
    }
}
