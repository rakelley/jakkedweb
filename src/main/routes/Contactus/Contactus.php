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
namespace main\routes\Contactus;

/**
 * RouteController for contact view and form action
 */
class Contactus extends \rakelley\jhframe\classes\RouteController
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController:routes
     */
    protected $routes = [
        'get' => [
            '/index/' => 'index',
        ],
        'post' => [
            '/contact/' => 'contact',
        ]
    ];


    /**
     * Composite index view
     */
    public function Index()
    {
        $this->standardView('Index');
    }


    /**
     * Contact form action
     */
    public function Contact()
    {
        $this->standardAction('Contact');
    }
}
