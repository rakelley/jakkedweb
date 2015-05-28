<?php
namespace test\main\repositories;

/**
 * @coversDefaultClass \main\repositories\Alertbanner
 */
class AlertbannerTest extends \test\helpers\cases\Base
{
    protected $bannerModelMock;


    protected function setUp()
    {
        $bannerModelClass = '\main\models\Alertbanner';
        $testedClass = '\main\repositories\Alertbanner';

        $this->bannerModelMock = $this->getMockBuilder($bannerModelClass)
                                      ->disableOriginalConstructor()
                                      ->getMock();

        $this->testObj = new $testedClass($this->bannerModelMock);
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->bannerModelMock, 'bannerModel',
                                     $this->testObj);
    }


    /**
     * @covers ::getBanner
     * @depends testConstruct
     */
    public function testGetBanner()
    {
        $value = ['foo' => 'bar'];

        $this->bannerModelMock->expects($this->once())
                              ->method('__get')
                              ->with($this->identicalTo('banner'))
                              ->willReturn($value);

        $this->assertEquals($value, $this->testObj->getBanner());
    }


    /**
     * @covers ::setBanner
     * @depends testConstruct
     */
    public function testSetBanner()
    {
        $value = ['foo' => 'bar'];

        $this->bannerModelMock->expects($this->once())
                              ->method('__set')
                              ->with($this->identicalTo('banner'),
                                     $this->identicalTo($value));

        $this->testObj->setBanner($value);
    }
}
