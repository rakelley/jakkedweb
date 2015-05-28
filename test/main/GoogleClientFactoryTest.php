<?php
namespace test\main;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\GoogleClientFactory
 */
class GoogleClientFactoryTest extends \test\helpers\cases\Base
{
    protected $configMock;


    protected function setUp()
    {
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $testedClass = '\main\GoogleClientFactory';

        $this->configMock = $this->getMock($configInterface);

        $mockedMethods = [
            'getConfig',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('getConfig')
                      ->willReturn($this->configMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $name = 'foobar';
        $key = 'abcde';

        $this->configMock->expects($this->at(0))
                         ->method('Get')
                         ->with($this->identicalTo('SECRETS'),
                                $this->identicalTo('google_appname'))
                         ->willReturn($name);
        $this->configMock->expects($this->at(1))
                         ->method('Get')
                         ->with($this->identicalTo('SECRETS'),
                                $this->identicalTo('google_apikey'))
                         ->willReturn($key);

        Utility::callConstructor($this->testObj);
        $this->assertAttributeEquals($name, 'appName', $this->testObj);
        $this->assertAttributeEquals($key, 'apiKey', $this->testObj);
    }


    /**
     * @covers ::getProduct
     */
    public function testGetProduct()
    {
        $expectedType = '\Google_Client';
        $properties = ['appName' => 'foobar', 'apiKey' => 'abcde'];

        Utility::setProperties($properties, $this->testObj);

        $result = $this->testObj->getProduct();
        $this->assertTrue($result instanceof $expectedType);
    }

    /**
     * @covers ::getProduct
     * @depends testGetProduct
     */
    public function testGetProductNoName()
    {
        $properties = ['appName' => null, 'apiKey' => 'abcde'];

        Utility::setProperties($properties, $this->testObj);

        $this->setExpectedException('\RuntimeException');
        $this->testObj->getProduct();
    }

    /**
     * @covers ::getProduct
     * @depends testGetProduct
     */
    public function testGetProductNoKey()
    {
        $properties = ['appName' => 'foobar', 'apiKey' => null];

        Utility::setProperties($properties, $this->testObj);

        $this->setExpectedException('\RuntimeException');
        $this->testObj->getProduct();
    }
}
