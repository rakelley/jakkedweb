<?php
namespace test\main\templates\Main;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\templates\Main\Main
 */
class MainTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\MockSubViews;

    protected $configMock;
    protected $cacheMock;
    protected $quoteMock;


    protected function setUp()
    {
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $cacheInterface = '\rakelley\jhframe\interfaces\services\IKeyValCache';
        $quoteClass = '\main\repositories\Quotes';
        $testedClass = '\main\templates\Main\Main';

        $this->configMock = $this->getMock($configInterface);
        $this->configMock->method('Get')
                         ->with($this->identicalTo('APP'),
                                $this->identicalTo('base_path'))
                         ->willReturn('http://example.com');

        $this->cacheMock = $this->getMock($cacheInterface);

        $this->quoteMock = $this->getMockBuilder($quoteClass)
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->quoteMock->method('getRandom')
                        ->with($this->isType('int'))
                        ->willReturn(['lorem ipsum quote']);

        $mockedMethods = [
            'getConfig',//trait implemented
            'getCalledNamespace',//trait implemented
            'getLocator',//trait implemented
            'interpolatePlaceholders',//trait implemented
            'makeSubViews',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->setConstructorArgs([$this->cacheMock,
                                                    $this->quoteMock])
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
        $this->assertAttributeEquals($this->cacheMock, 'cache', $this->testObj);
        $this->assertAttributeEquals($this->quoteMock, 'quoteRepo',
                                     $this->testObj);
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
     * @depends testConstruct
     * @depends testGetSubViewList
     */
    public function testMakeCompositeCacheHit()
    {
        $mainContent = 'foobarbazbat main';

        $subViews = $this->mockSubViews(true);

        $this->cacheMock->expects($this->exactly(2))
                        ->method('Read')
                        ->willReturn($subViews);

        $this->testObj->expects($this->once())
                      ->method('interpolatePlaceholders')
                      ->with($this->isType('string'), $this->isType('array'))
                      ->will($this->returnArgument(0));

        $result = $this->testObj->makeComposite($mainContent);
        $this->assertTrue(strlen($result) > 1);
        $this->assertContains($mainContent, $result);
    }


    /**
     * @covers ::makeComposite
     * @covers ::<private>
     * @depends testMakeCompositeCacheHit
     */
    public function testMakeCompositeCacheMiss()
    {
        $mainContent = 'foobarbazbat main';

        $this->mockSubViews();

        $this->cacheMock->expects($this->once())
                        ->method('Read')
                        ->willReturn(false);
        $this->cacheMock->expects($this->once())
                        ->method('Write')
                        ->with($this->isType('array'), $this->isType('string'));

        $this->testObj->expects($this->once())
                      ->method('makeSubViews');
        $this->testObj->method('interpolatePlaceholders')
                      ->will($this->returnArgument(0));

        $result = $this->testObj->makeComposite($mainContent);
        $this->assertTrue(strlen($result) > 1);
    }


    /**
     * @covers ::makeComposite
     * @covers ::<private>
     * @depends testMakeCompositeCacheHit
     */
    public function testMakeCompositeWithMetadata()
    {
        $mainContent = 'foobarbazbat main';
        $metadata = ['title' => 'foobar', 'description' => 'bazbat'];

        $subViews = $this->mockSubViews(true);

        $this->cacheMock->method('Read')
                        ->willReturn($subViews);

        $this->testObj->method('interpolatePlaceholders')
                      ->will($this->returnArgument(0));

        $result = $this->testObj->makeComposite($mainContent, $metadata);
        $this->assertTrue(strlen($result) > 1);
    }


    /**
     * @covers ::makeComposite
     * @covers ::<private>
     * @depends testMakeCompositeWithMetadata
     */
    public function testMakeCompositeWithLongTitle()
    {
        $mainContent = 'foobarbazbat main';
        $title = implode('', array_fill(0, 3, md5(rand())));
        $metadata = ['title' => $title, 'description' => 'bazbat'];

        $subViews = $this->mockSubViews(true);

        $this->cacheMock->method('Read')
                        ->willReturn($subViews);

        $this->testObj->method('interpolatePlaceholders')
                      ->will($this->returnArgument(0));

        $result = $this->testObj->makeComposite($mainContent, $metadata);
        $this->assertTrue(strlen($result) > 1);
    }
}
