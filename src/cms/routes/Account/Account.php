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

namespace cms\routes\Account;

use \cms\resources\Permissions;

/**
 * RouteController for User Account self creation/deletion/updating
 */
class Account extends \rakelley\jhframe\classes\RouteController
{
    use \cms\UserSpecificRoutes,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\controller\Authenticated;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController::$routes
     */
    protected $routes = [
        'get' => [
            '/index/'   => 'index',
        ],
        'post' => [
            '/create/'       => 'create',
            '/delete/'       => 'delete',
            '/setpassword/'  => 'setpassword',
            '/setphoto/'     => 'setphoto',
            '/setprofile/'   => 'setprofile',
        ]
    ];
    /**
     * Permission used to authenticate routes
     * @var string
     */
    protected $permission = Permissions::ALLUSERS;


    /**
     * User Account index view
     */
    public function Index()
    {
        $this->routeAuth();

        $parameters = ['username' => $this->getUserProperty('username')];
        $this->standardView('Index', $parameters, false);
    }


    /**
     * Create new account.
     * Public facing, does not require authorization.
     */
    public function Create()
    {
        $this->standardAction('Create');
    }


    /**
     * User deleting own account
     */
    public function Delete()
    {
        $this->routeAuth();

        $parameters = ['username' => $this->getUserProperty('username')];
        $this->standardAction('Delete', $parameters);
    }


    /**
     * User updating own password
     */
    public function setPassword()
    {
        $this->routeAuth();

        $parameters = ['username' => $this->getUserProperty('username')];
        $this->standardAction('ChangePassword', $parameters);
    }


    /**
     * User updating own photo
     */
    public function setPhoto()
    {
        $this->routeAuth();

        $parameters = ['username' => $this->getUserProperty('username')];
        $this->standardAction('ChangePhoto', $parameters);
    }


    /**
     * User updating own profile
     */
    public function setProfile()
    {
        $this->routeAuth();

        $parameters = ['username' => $this->getUserProperty('username')];
        $this->standardAction('ChangeProfile', $parameters);
    }
}
