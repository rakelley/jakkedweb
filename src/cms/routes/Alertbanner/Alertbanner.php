<?php
/**
 * @package jakkedweb
 * @subpackage cms
 * 
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

namespace cms\routes\Alertbanner;

use \cms\resources\Permissions;

/**
 * RouteController for updating Alertbanner site widget
 */
class Alertbanner extends \rakelley\jhframe\classes\RouteController
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\controller\Authenticated;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController::$routes
     */
    protected $routes = [
        'get'  => [
            '/index/' => 'index',
        ],
        'post' => [
            '/update/' => 'update',
        ]
    ];
    /**
     * Permission to use for authenticating routes
     * @var string
     */
    protected $permission = Permissions::WIDGETS;


    /**
     * Update Form view
     */
    public function Index()
    {
        $this->routeAuth();

        $this->standardView('UpdateForm');
    }


    /**
     * Update Form action
     */
    public function Update()
    {
        $this->routeAuth();

        $this->standardAction('Update');
    }
}
