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
namespace main\routes\Rss;

/**
 * RouteController for RSS feed
 */
class Rss extends \rakelley\jhframe\classes\RouteController
{
    protected $routes = [
        'get' => [
            '/index/' => 'index',
        ],
    ];


    /**
     * Main RSS feed
     */
    public function Index()
    {
        $this->standardView('Index');
    }
}
