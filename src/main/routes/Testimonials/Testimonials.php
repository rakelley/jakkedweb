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
namespace main\routes\Testimonials;

/**
 * RouteController for customer testimonials
 */
class Testimonials extends \rakelley\jhframe\classes\RouteController
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController::$routes
     */
    protected $routes = [
        'get' => [
            '/index/' => 'index',
        ],
        'post' => [
            '/add/' => 'add',
        ]
    ];


    /**
     * Testimonial view
     */
    public function Index()
    {
        $this->standardView('Index', null, false);
    }


    /**
     * Action to add new testimonial
     */
    public function Add()
    {
        $this->standardAction('Add');
    }
}
