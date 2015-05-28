<?php
namespace test\main;

/**
 * @coversDefaultClass \main\FlatView
 */
class FlatViewTest extends \test\helpers\cases\Base
{

    protected function setUp()
    {
        $testedClass = '\main\FlatView';

        $mockedMethods = [
            'mapNameToFile',//implemented by parent
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
    }


    /**
     * @covers ::setParameters
     */
    public function testSetParameters()
    {
        $parameters = ['view' => 'viewname', 'namespace' => 'foo\bar'];
        $file = 'name\spaced\file';

        $this->testObj->method('mapNameToFile')
                      ->with($this->identicalTo($parameters['view']),
                             $this->identicalTo($parameters['namespace']))
                      ->willReturn($file);

        $this->testObj->setParameters($parameters);
        $this->assertAttributeEquals($parameters['view'], 'metaRoute',
                                     $this->testObj);
        $this->assertAttributeEquals($file, 'file', $this->testObj);
    }


    /**
     * @covers ::setParameters
     * @depends testSetParameters
     */
    public function testSetParametersInvalidView()
    {
        $parameters = ['namespace' => 'foo\bar'];

        $this->setExpectedException('\BadMethodCallException');
        $this->testObj->setParameters($parameters);
    }


    /**
     * @covers ::setParameters
     * @depends testSetParameters
     */
    public function testSetParametersInvalidNamespace()
    {
        $parameters = ['view' => 'viewname'];

        $this->setExpectedException('\BadMethodCallException');
        $this->testObj->setParameters($parameters);
    }
}
