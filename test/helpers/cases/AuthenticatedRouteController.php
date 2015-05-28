<?php
namespace test\helpers\cases;

/**
 * Custom testcase covering common functionality of tests for routecontrollers
 */
abstract class AuthenticatedRouteController extends RouteController
{

    protected function setUp()
    {
        $mockedMethods = [
            'standardView',//implemented by parent
            'standardAction',//implemented by parent
            'routeAuth',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($this->testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();

        $this->createRoutedMethodsList();
    }
}
