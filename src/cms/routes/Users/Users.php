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

namespace cms\routes\Users;

use \cms\resources\Permissions;

/**
 * RouteController for admins to manage users
 */
class Users extends \rakelley\jhframe\classes\RouteController
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\controller\Authenticated,
        \rakelley\jhframe\traits\controller\AcceptsArguments;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController::$routes
     */
    protected $routes = [
        'get' => [
            '/index/' => 'index',
            '/edit/'  => 'edit',
        ],
        'post' => [
            '/delete/'  => 'delete',
            '/roles/'   => 'setRoles',
        ]
    ];
    /**
     * Permission used to authorize routes
     * @var string
     */
    protected $permission = Permissions::USERS;


    /**
     * View of all users
     */
    public function Index()
    {
        $this->routeAuth();

        $this->standardView('Index');
    }

    /**
     * View for editing a user
     */
    public function Edit()
    {
        $this->routeAuth();

        $arguments = [
            'required' => ['username' => 'default'],
            'method' => 'get',
        ];
        $parameters = $this->getArguments($arguments);

        $this->standardView('Edit', $parameters);
    }


    /**
     * Action for deleting a user
     */
    public function Delete()
    {
        $this->routeAuth();

        $this->standardAction('Delete');
    }

    /**
     * Action for setting a user's roles
     */
    public function setRoles()
    {
        $this->routeAuth();

        $this->standardAction('Roles');
    }
}
