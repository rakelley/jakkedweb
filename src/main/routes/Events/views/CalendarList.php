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
namespace main\routes\Events\views;

/**
 * View for Google Calendar widget
 */
class CalendarList extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * GoogleCalendar repo instance
     * @var object
     */
    private $gCal;


    /**
     * @param \main\repositories\GoogleCalendar $gCal
     */
    function __construct(
        \main\repositories\GoogleCalendar $gCal
    ) {
        $this->gCal = $gCal;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->gCal->getUpcomingEvents();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        if ($this->data) {
            $events = implode('', array_map([$this, 'fillEvent'], $this->data));
            $this->viewContent = '<ul class="gcal">' . $events . '</ul>';
        } else {
            $this->viewContent =
                '<p class="alignment-center">No Upcoming Events</p>';
        }
    }


    /**
     * Generates markup for a single event
     * 
     * @param  array  $event Event properties
     * @return string
     */
    private function fillEvent(array $event)
    {
        return <<<HTML
<li>
    {$event['date']} -
    <a href="{$event['link']}" alt="See This Event on Google Calendar">
        {$event['title']}
    </a>
</li>
HTML;
    }
}
