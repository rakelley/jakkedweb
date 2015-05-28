<?php
namespace test\cms\templates\Cms;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \cms\templates\Cms\Cms
 */
class CmsTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews;


    protected function setUp()
    {
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $testedClass = '\cms\templates\Cms\Cms';

        $configMock = $this->getMock($configInterface);
        $configMock->method('Get')
                   ->with($this->identicalTo('APP'),
                          $this->identicalTo('base_path'))
                   ->willReturn('http://example.com');

        $mockedMethods = [
            'getConfig',//trait implemented
            'getCalledNamespace',//trait implemented
            'getLocator',//trait implemented
            'makeSubViews',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('getConfig')
                      ->willReturn($configMock);
        Utility::callConstructor($this->testObj);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeNotEmpty('basePath', $this->testObj);
    }


    /**
     * @covers ::getSubViewList
     */
    public function testGetSubViewList()
    {
        $this->standardGetSubViewListTest();
    }


    /**
     * @covers ::makeComposite
     * @covers ::<private>
     * @depends testGetSubViewList
     */
    public function testMakeComposite()
    {
        $this->mockSubViews();

        $content = 'lorem ipsum';

        $page = $this->testObj->makeComposite($content);
        $this->assertTrue(strlen($content) > 1);
    }
}
