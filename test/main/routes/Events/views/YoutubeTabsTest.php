<?php
namespace test\main\routes\Events\views;

/**
 * @coversDefaultClass \main\routes\Events\views\YoutubeTabs
 */
class YoutubeTabsTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider,
        \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\Youtube';
        $testedClass = '\main\routes\Events\views\YoutubeTabs';

        $this->repoMock = $this->getMockBuilder($repoClass)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->testObj = new $testedClass($this->repoMock);
    }


    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->repoMock, 'yt', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $videos = ['lorem', 'ipsum', 'dolor'];

        $this->repoMock->expects($this->once())
                       ->method('getRecentVideos')
                       ->willReturn($videos);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($videos, 'data', $this->testObj);
    }


    /**
     * @covers ::constructView
     * @covers ::<private>
     * @depends testFetchData
     * @dataProvider booleanCaseProvider
     */
    public function testConstructView($withData)
    {
        if ($withData) {
            $this->testFetchData();
        }

        $this->standardConstructViewTest();
    }
}
