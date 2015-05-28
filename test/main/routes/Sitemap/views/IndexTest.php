<?php
namespace test\main\routes\Sitemap\views;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\routes\Sitemap\views\Index
 */
class IndexTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\PageData';
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $testedClass = '\main\routes\Sitemap\views\Index';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $configMock = $this->getMock($configInterface);
        $configMock->method('Get')
                   ->with($this->identicalTo('APP'),
                          $this->identicalTo('base_path'))
                   ->willReturn('http://example.com/');

        $mockedMethods = [
            'getConfig',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('getConfig')
                      ->willReturn($configMock);
        Utility::callConstructor($this->testObj, [$this->repoMock]);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'pages', $this->testObj);
        $this->assertAttributeNotEmpty('basePath', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $pages = [
            ['route' => 'index', 'priority' => 1.0],
            ['route' => 'foo/bar', 'priority' => 0.8],
            ['route' => 'bazbat', 'priority' => 0.4],
            ['route' => 'lorem/index', 'priority' => 0.2],
        ];
        $expected = [
            ['route' => '', 'priority' => 1.0],
            ['route' => 'foo/bar', 'priority' => 0.8],
            ['route' => 'bazbat', 'priority' => 0.4],
            ['route' => 'lorem/index', 'priority' => 0.2],
        ];

        $this->repoMock->expects($this->once())
                       ->method('getAll')
                       ->willReturn($pages);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($expected, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     */
    public function testConstructView()
    {
        $this->testFetchData();

        $this->standardConstructViewTest();
    }
}
