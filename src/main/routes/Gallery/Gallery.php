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
namespace main\routes\Gallery;

/**
 * RouteController for photo galleries
 */
class Gallery extends \rakelley\jhframe\classes\RouteController
{
    protected $routes = [
        'get' => [
            '/index/'     => 'index',
            '/shelters/'  => 'shelters',
            '/\b[\w]+\b/' => 'gallery',
        ],
    ];


    /**
     * Index of galleries
     */
    public function Index()
    {
        $this->standardView('Index');
    }


    /**
     * Animal shelter gallery
     */
    public function Shelters()
    {
        $this->standardView('Shelters');
    }


    /**
     * Standard galleries
     * 
     * @param string $gallery Name of gallery to show
     */
    public function Gallery($gallery)
    {
        $this->standardView('Gallery', ['gallery' => $gallery]);
    }
}
