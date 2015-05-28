<?php
namespace test\main\repositories;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\repositories\PetFinderAPI
 */
class PetFinderAPITest extends \test\helpers\cases\Base
{
    protected $curlMock;
    protected $filterMock;


    protected function setUp()
    {
        $curlClass = '\rakelley\jhframe\classes\CurlAbstractor';
        $filterInterface = '\rakelley\jhframe\interfaces\services\IFilter';
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $testedClass = '\main\repositories\PetFinderAPI';

        $this->curlMock = $this->getMockBuilder($curlClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->filterMock = $this->getMock($filterInterface);

        $configMock = $this->getMock($configInterface);
        $configMock->method('GET')
                   ->with($this->identicalTo('SECRETS'),
                          $this->identicalTo('PFAPI_KEY'))
                   ->willReturn('12345');

        $mockedMethods = [
            'getConfig',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('getConfig')
                      ->willReturn($configMock);
        Utility::callConstructor($this->testObj, [$this->curlMock,
                                                  $this->filterMock]);
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->curlMock, 'curl', $this->testObj);
        $this->assertAttributeEquals($this->filterMock, 'filter',
                                     $this->testObj);
        $this->assertAttributeNotEmpty('key', $this->testObj);
    }



    /**
     * @covers ::getLatest
     * @covers ::<protected>
     * @depends testConstruct
     */
    public function testGetLatest()
    {
        $key = $this->readAttribute($this->testObj, 'key');
        $results = [
            'petfinder' => ['pets' => ['pet' => [
                [
                    'name' => ['$t' => 'Foo'],
                    'id' => ['$t' => 014],
                    'media' => ['photos' => ['photo' => [
                        ['$t' => 'foo014l.jpg', '@size' => 'l'],
                        ['$t' => 'foo014x.jpg', '@size' => 'x'],
                        ['$t' => 'foo014s.jpg', '@size' => 's'],
                    ]]],
                ],
                [
                    'name' => ['$t' => 'Bar'],
                    'id' => ['$t' => 028],
                    'media' => ['photos' => ['photo' => [
                        ['$t' => 'bar028l.jpg', '@size' => 'l'],
                        ['$t' => 'bar028x.jpg', '@size' => 'x'],
                        ['$t' => 'bar028s.jpg', '@size' => 's'],
                    ]]],
                ],
                [
                    'name' => ['$t' => 'Baz'],
                    'id' => ['$t' => 042],
                    'media' => ['photos' => ['photo' => [
                        ['$t' => 'baz042l.jpg', '@size' => 'l'],
                        ['$t' => 'baz042x.jpg', '@size' => 'x'],
                        ['$t' => 'baz042s.jpg', '@size' => 's'],
                    ]]],
                ],
            ]]],
        ];
        $resultCount = count($results['petfinder']['pets']['pet']);
        $blob = json_encode($results);
        $expected = [
            ['name' => 'Foo', 'number' => 014, 'img' => 'foo014x.jpg'],
            ['name' => 'Bar', 'number' => 028, 'img' => 'bar028x.jpg'],
            ['name' => 'Baz', 'number' => 042, 'img' => 'baz042x.jpg'],
        ];

        $this->curlMock->expects($this->once())
                       ->method('newRequest')
                       ->with($this->stringContains($key))
                       ->will($this->returnSelf());
        $this->curlMock->expects($this->once())
                       ->method('setReturn')
                       ->will($this->returnSelf());
        $this->curlMock->expects($this->once())
                       ->method('Execute')
                       ->willReturn($blob);
        $this->curlMock->expects($this->once())
                       ->method('Close');

        $this->filterMock->expects($this->exactly($resultCount))
                         ->method('Word')
                         ->will($this->returnArgument(0));
        $this->filterMock->expects($this->exactly($resultCount))
                         ->method('Int')
                         ->will($this->returnArgument(0));
        $this->filterMock->expects($this->exactly($resultCount))
                         ->method('Url')
                         ->will($this->returnArgument(0));

        $this->assertEquals($expected, $this->testObj->getLatest());
    }

    /**
     * @covers ::getLatest
     * @covers ::<protected>
     * @depends testGetLatest
     */
    public function testGetLatestFailedRequest()
    {
        $this->curlMock->method('newRequest')
                       ->will($this->returnSelf());
        $this->curlMock->method('setReturn')
                       ->will($this->returnSelf());
        $this->curlMock->method('Execute')
                       ->willReturn(null);

        $this->setExpectedException('\RuntimeException');
        $this->testObj->getLatest();
    }

    /**
     * @covers ::getLatest
     * @covers ::<protected>
     * @depends testGetLatest
     */
    public function testGetLatestEmptyRequest()
    {
        $results = [
            'petfinder' => ['pets' => ['pet' => null]],
        ];
        $blob = json_encode($results);

        $this->curlMock->method('newRequest')
                       ->will($this->returnSelf());
        $this->curlMock->method('setReturn')
                       ->will($this->returnSelf());
        $this->curlMock->method('Execute')
                       ->willReturn($blob);

        $this->setExpectedException('\RuntimeException');
        $this->testObj->getLatest();
    }

    /**
     * @covers ::getLatest
     * @covers ::<protected>
     * @depends testGetLatest
     */
    public function testGetLatestBadAnimals()
    {
        $results = [
            'petfinder' => ['pets' => ['pet' => [
                [//valid
                    'name' => ['$t' => 'Foo'],
                    'id' => ['$t' => 014],
                    'media' => ['photos' => ['photo' => [
                        ['$t' => 'foo014l.jpg', '@size' => 'l'],
                        ['$t' => 'foo014x.jpg', '@size' => 'x'],
                        ['$t' => 'foo014s.jpg', '@size' => 's'],
                    ]]],
                ],
                [//no photos
                    'name' => ['$t' => 'Bar'],
                    'id' => ['$t' => 028],
                    'media' => [],
                ],
                [//no x size photo
                    'name' => ['$t' => 'Baz'],
                    'id' => ['$t' => 042],
                    'media' => ['photos' => ['photo' => [
                        ['$t' => 'baz042l.jpg', '@size' => 'l'],
                        ['$t' => 'baz042s.jpg', '@size' => 's'],
                    ]]],
                ],
                [//bad name
                    'name' => ['$t' => 'A056'],
                    'id' => ['$t' => 056],
                    'media' => ['photos' => ['photo' => [
                        ['$t' => 'a056l.jpg', '@size' => 'l'],
                        ['$t' => 'a056x.jpg', '@size' => 'x'],
                        ['$t' => 'a056s.jpg', '@size' => 's'],
                    ]]],
                ],
            ]]],
        ];
        $blob = json_encode($results);
        $expected = [
            ['name' => 'Foo', 'number' => 014, 'img' => 'foo014x.jpg'],
        ];

        $this->curlMock->method('newRequest')
                       ->will($this->returnSelf());
        $this->curlMock->method('setReturn')
                       ->will($this->returnSelf());
        $this->curlMock->method('Execute')
                       ->willReturn($blob);

        $this->filterMock->method('Word')
                         ->will($this->returnArgument(0));
        $this->filterMock->method('Int')
                         ->will($this->returnArgument(0));
        $this->filterMock->method('Url')
                         ->will($this->returnArgument(0));

        $this->assertEquals($expected, $this->testObj->getLatest());
    }
}
