<?php
namespace test\main\templates\Main;

/**
 * @coversDefaultClass \main\templates\Main\Alertbanner
 */
class AlertbannerTest extends \test\helpers\cases\Base
{
    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Alertbanner';
        $testedClass = '\main\templates\Main\Alertbanner';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    public function bannerProvider()
    {
        return [
            [//values set
                ['href' => 'lorem', 'title' => 'ipsum']
            ],
            [//no values set
                null
            ],
        ];
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'banner', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     * @dataProvider bannerProvider
     */
    public function testFetchData($data)
    {
        $this->repoMock->expects($this->once())
                       ->method('getBanner')
                       ->willReturn($data);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($data, 'bannerData', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @depends testFetchData
     * @dataProvider bannerProvider
     */
    public function testConstructView($data)
    {
        $this->repoMock->method('getBanner')
                       ->willReturn($data);
        $this->testObj->fetchData();

        $this->testObj->constructView();
        $length = strlen($this->readAttribute($this->testObj, 'viewContent'));
        if ($data) {
            $this->assertTrue($length > 1);
        } else {
            $this->assertTrue($length === 1);
        }
    }
}
