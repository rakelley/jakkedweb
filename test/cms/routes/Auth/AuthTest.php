<?php
namespace test\cms\routes\Auth;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\routes\Auth\Auth
 */
class AuthTest extends \test\helpers\cases\RouteController
{
    protected $actionMock;


    protected function setUp()
    {
        $actionInterface =
            '\rakelley\jhframe\interfaces\services\IActionController';
        $testedClass = '\cms\routes\Auth\Auth';

        $this->actionMock = $this->getMock($actionInterface);

        $mockedMethods = [
            'standardView',//parent implemented
            'standardAction',//parent implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
        Utility::setProperties(['actionController' => $this->actionMock],
                               $this->testObj);

        $this->createRoutedMethodsList();
    }


    /**
     * @covers ::LogOut
     */
    public function testLogOut()
    {
        $this->assertContains('logout', $this->routedMethods);

        $this->actionMock->expects($this->once())
                         ->method('executeAction')
                         ->with($this->isType('string'));

        $this->testObj->expects($this->once())
                      ->method('standardView')
                      ->with($this->isType('string'));

        $this->testObj->LogOut();
    }


    /**
     * @covers ::LogIn
     */
    public function testLogIn()
    {
        $this->assertContains('login', $this->routedMethods);

        $this->testObj->expects($this->once())
                      ->method('standardAction')
                      ->with($this->isType('string'));

        $this->testObj->LogIn();
    }
}
