<?php
/**
 * @package jakkedweb
 * @subpackage main
 * 
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */
namespace main\repositories;

/**
 * Repository for interacting with the Google Calendar service
 */
class GoogleCalendar extends \rakelley\jhframe\classes\Repository
{
    use \rakelley\jhframe\traits\ConfigAware;

    /**
     * Google_Service_Calendar instance
     * @var object
     */
    protected $calendar;
    /**
     * IFilter instance
     * @var object
     */
    protected $filter;
    /**
     * Calendar ID
     * @var string
     */
    protected $id;


    function __construct(
        \rakelley\jhframe\interfaces\services\IFilter $filter,
        \Google_Service_Calendar $calendar
    ) {
        $this->filter = $filter;
        $this->calendar = $calendar;

        $this->id = $this->getConfig()->Get('SECRETS', 'google_calendarid');
    }


    /**
     * Get list of upcoming events
     * 
     * @return array|null Null if no upcoming events
     */
    public function getUpcomingEvents()
    {
        $params = [
            'timeMin'      => date(\DateTime::RFC3339),
            'singleEvents' => true,
            'orderBy'      => 'startTime',
        ];
        $events = $this->calendar->events->listEvents($this->id, $params)
                                         ->getItems();

        if ($events) {
            $result = array_filter(array_map([$this, 'filterEvent'], $events));
        } else {
            $result = null;
        }

        return ($result) ?: null;
    }


    /**
     * Converts event object into usable array with sanitized values.
     * 
     * @param  object $event \Google_Service_Calendar_Event
     * @return array|null    Incomplete/malformed events return null
     *     string 'date'  Event date in m/d format
     *     string 'title' Event title
     *     string 'link'  Event URL
     */
    protected function filterEvent(\Google_Service_Calendar_Event $event)
    {
        $date = $this->filter->Date($event->getStart()->getDateTime(), 'm/d');
        $title = $this->filter->plainText($event->getSummary());
        $link = $this->filter->Url($event->getHtmlLink());

        if ($date && $title && $link) {
            return ['date' => $date, 'title' => $title, 'link' => $link];
        } else {
            return null;
        }
    }
}
