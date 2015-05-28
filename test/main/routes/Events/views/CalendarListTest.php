<?php
namespace test\main\routes\Events\views;

/**
 * @coversDefaultClass \main\routes\Events\views\CalendarList
 */
class CalendarListTest extends \test\helpers\cases\Base
{
    use \test\helpers\traits\BooleanCaseProvider,
        \test\helpers\traits\TestsConstructView;

    protected $repoMock;


    protected function setUp()
    {
        $repoClass = '\main\repositories\GoogleCalendar';
        $testedClass = '\main\routes\Events\views\CalendarList';

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
        $this->assertAttributeEquals($this->repoMock, 'gCal', $this->testObj);
    }


    /**
     * @covers ::fetchData
     * @depends testConstruct
     */
    public function testFetchData()
    {
        $events = [
            [
                'date' => '03/14',
                'link' => 'http://example.com/1',
                'title' => 'foobar 1',
            ],
            [
                'date' => '04/15',
                'link' => 'http://example.com/2',
                'title' => 'foobar 2',
            ],
            [
                'date' => '05/16',
                'link' => 'http://example.com/3',
                'title' => 'foobar 3',
            ],
        ];

        $this->repoMock->expects($this->once())
                       ->method('getUpcomingEvents')
                       ->willReturn($events);

        $this->testObj->fetchData();
        $this->assertAttributeEquals($events, 'data', $this->testObj);
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
