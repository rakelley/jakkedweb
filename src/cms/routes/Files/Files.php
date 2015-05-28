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

namespace cms\routes\Files;

use \cms\resources\Permissions;

/**
 * RouteController for file uploads
 */
class Files extends \rakelley\jhframe\classes\RouteController
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\controller\Authenticated;

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
     * Permission to use in route access validation
     * @var string
     */
    protected $permission = Permissions::FILES;


    /**
     * Upload form view
     */
    public function Index()
    {
        $this->routeAuth();

        $this->standardView('AddForm');
    }


    /**
     * Upload action
     */
    public function Add()
    {
        $this->routeAuth();

        $this->standardAction('Add');
    }
}
