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

namespace cms\routes\Animals;

use \cms\resources\Permissions;

/**
 * RouteController for manually updating animal gallery
 */
class Animals extends \rakelley\jhframe\classes\RouteController
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
     * Permission to use in authenticating routes
     * @var string
     */
    protected $permission = Permissions::WIDGETS;


    /**
     * Update form view
     */
    public function Index()
    {
        $this->routeAuth();

        $this->standardView('UpdateForm');
    }


    /**
     * Update action
     */
    public function Update()
    {
        $this->routeAuth();

        $this->standardAction('Update');
    }
}
