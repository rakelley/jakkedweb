<?php
namespace test\helpers\cases;

/**
 * Custom testcase covering common functionality of tests for routecontrollers
 */
abstract class RouteController extends Base
{
    /**
     * List of all class methods which have an assigned regex route
     * @var array
     */
    protected $routedMethods = [];


    protected function setUp()
    {
        $mockedMethods = [
            'standardView',//implemented by parent
            'standardAction',//implemented by parent
        ];
        $this->testObj = $this->getMockBuilder($this->testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();

        $this->createRoutedMethodsList();
    }

    /**
     * Generates routedMethods
     * 
     * @return void
     */
    protected function createRoutedMethodsList()
    {
        $routes = $this->readAttribute($this->testObj, 'routes');
        $get = (isset($routes['get'])) ? array_values($routes['get']) : [];
        $post = (isset($routes['post'])) ? array_values($routes['post']) : [];
        $this->routedMethods = array_merge($get, $post);
    }
}
