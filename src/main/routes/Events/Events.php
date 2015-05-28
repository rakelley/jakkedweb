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
namespace main\routes\Events;

/**
 * RouteController for event views
 */
class Events extends \rakelley\jhframe\classes\RouteController
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController::$routes
     */
    protected $routes = [
        'get' => [
            '/calendar/' => 'calendar',
            '/news/'     => 'news',
            '/videos/'   => 'videos',
        ]
    ];


    /**
     * Upcoming events calendar, not cacheable
     */
    public function Calendar()
    {
        $this->standardView('CalendarList', null, false);
    }

    /**
     * Recent articles
     */
    public function News()
    {
        $this->standardView('News');
    }

    /**
     * Recent videos, not cacheable
     */
    public function Videos()
    {
        $this->standardView('YoutubeTabs', null, false);
    }
}
