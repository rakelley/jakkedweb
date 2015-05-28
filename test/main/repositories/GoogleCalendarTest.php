<?php
namespace test\main\repositories;

use \test\helpers\PHPUnitUtil as Utility;

/**
 * @coversDefaultClass \main\repositories\GoogleCalendar
 */
class GoogleCalendarTest extends \test\helpers\cases\Base
{
    protected $calendarMock;
    protected $filterMock;
    protected $eventsMock;
    protected $eventMock;


    protected function setUp()
    {
        $calendarRealClass = 'Google_Service_Calendar';
        $filterInterface = '\rakelley\jhframe\interfaces\services\IFilter';
        $eventRealClass = 'Google_Service_Calendar_Event';
        $configInterface = '\rakelley\jhframe\interfaces\services\IConfig';
        $testedClass = '\main\repositories\GoogleCalendar';

        $this->filterMock = $this->getMock($filterInterface);

        $this->eventsMock = $this->getMockBuilder('\stdClass')
                                 ->disableOriginalConstructor()
                                 ->setMethods(['listEvents', 'getItems'])
                                 ->getMock();

        $this->calendarMock = $this->getMockBuilder('\stdClass')
                                   ->setMockClassName($calendarRealClass)
                                   ->disableOriginalConstructor()
                                   ->getMock();
        $this->calendarMock->events = $this->eventsMock;

        $this->eventMock = $this->getMockBuilder('\stdClass')
                                ->setMockClassName($eventRealClass)
                                ->disableOriginalConstructor()
                                ->setMethods(['getStart', 'getDateTime',
                                              'getSummary', 'getHtmlLink'])
                                ->getMock();

        $configMock = $this->getMock($configInterface);
        $configMock->method('Get')
                   ->with($this->identicalTo('SECRETS'),
                          $this->identicalTo('google_calendarid'))
                   ->willReturn('1234');

        $mockedMethods = [
            'getConfig',//trait implemented
        ];
        $this->testObj = $this->getMockBuilder($testedClass)
                              ->disableOriginalConstructor()
                              ->setMethods($mockedMethods)
                              ->getMock();
        $this->testObj->method('getConfig')
                      ->willReturn($configMock);
        Utility::callConstructor($this->testObj, [$this->filterMock,
                                                  $this->calendarMock]);
    }


    /**
     * Method called by setUp
     * 
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals($this->filterMock, 'filter',
                                     $this->testObj);
        $this->assertAttributeEquals($this->calendarMock, 'calendar',
                                     $this->testObj);
        $this->assertAttributeNotEmpty('id', $this->testObj);
    }


    /**
     * @covers ::getUpcomingEvents
     * @covers ::<protected>
     * @depends testConstruct
     */
    public function testGetUpcomingEvents()
    {
        $id = $this->readAttribute($this->testObj, 'id');
        $eventList = [$this->eventMock, $this->eventMock, $this->eventMock];
        $eCount = count($eventList);
        $date = '03/14/15';
        $summary = 'lorem ipsum';
        $link = 'http://example.com';
        $expected = ['date' => $date, 'title' => $summary, 'link' => $link];

        $this->eventsMock->expects($this->once())
                         ->method('listEvents')
                         ->with($this->identicalTo($id), $this->isType('array'))
                         ->willReturn($this->eventsMock);
        $this->eventsMock->expects($this->once())
                         ->method('getItems')
                         ->willReturn($eventList);

        $this->eventMock->expects($this->exactly($eCount))
                        ->method('getStart')
                        ->willReturn($this->eventMock);
        $this->eventMock->expects($this->exactly($eCount))
                        ->method('getDateTime')
                        ->willReturn($date);
        $this->eventMock->expects($this->exactly($eCount))
                        ->method('getSummary')
                        ->willReturn($summary);
        $this->eventMock->expects($this->exactly($eCount))
                        ->method('getHtmlLink')
                        ->willReturn($link);

        $this->filterMock->expects($this->exactly($eCount))
                         ->method('Date')
                         ->with($this->identicalTo($date),
                                $this->isType('string'))
                         ->will($this->returnArgument(0));
        $this->filterMock->expects($this->exactly($eCount))
                         ->method('plainText')
                         ->with($this->identicalTo($summary))
                         ->will($this->returnArgument(0));
        $this->filterMock->expects($this->exactly($eCount))
                         ->method('Url')
                         ->with($this->identicalTo($link))
                         ->will($this->returnArgument(0));

        $result = $this->testObj->getUpcomingEvents();
        $this->assertTrue(count($result) === $eCount);
        $this->assertEquals($expected, $result[0]);
    }


    /**
     * @covers ::getUpcomingEvents
     * @depends testGetUpcomingEvents
     */
    public function testGetUpcomingEventsNoEvents()
    {
        $this->eventsMock->method('listEvents')
                         ->willReturn($this->eventsMock);
        $this->eventsMock->method('getItems')
                         ->willReturn([]);

        $this->assertEquals(null, $this->testObj->getUpcomingEvents());
    }

    /**
     * @covers ::getUpcomingEvents
     * @covers ::<protected>
     * @depends testGetUpcomingEvents
     */
    public function testGetUpcomingEventsBadEvent()
    {
        $badEvent = clone($this->eventMock);
        $eventList = [$this->eventMock, $badEvent, $this->eventMock];
        $date = '03/14/15';
        $summary = 'lorem ipsum';
        $link = 'http://example.com';

        $this->eventsMock->method('listEvents')
                         ->willReturn($this->eventsMock);
        $this->eventsMock->method('getItems')
                         ->willReturn($eventList);

        $this->eventMock->method('getStart')
                        ->willReturn($this->eventMock);
        $this->eventMock->method('getDateTime')
                        ->willReturn($date);
        $this->eventMock->method('getSummary')
                        ->willReturn($summary);
        $this->eventMock->method('getHtmlLink')
                        ->willReturn($link);

        $badEvent->method('getStart')
                 ->willReturn($badEvent);
        $badEvent->method('getDateTime')
                 ->willReturn(null);
        $badEvent->method('getSummary')
                 ->willReturn($summary);
        $badEvent->method('getHtmlLink')
                 ->willReturn($link);

        $this->filterMock->method('Date')
                         ->will($this->returnArgument(0));
        $this->filterMock->method('plainText')
                         ->will($this->returnArgument(0));
        $this->filterMock->method('Url')
                         ->will($this->returnArgument(0));

        $result = $this->testObj->getUpcomingEvents();
        $this->assertTrue(count($result) === count($eventList)-1);
    }
}
