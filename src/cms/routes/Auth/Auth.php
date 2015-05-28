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

namespace cms\routes\Auth;

/**
 * RouteController for user session creation and termination
 */
class Auth extends \rakelley\jhframe\classes\RouteController
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController::$routes
     */
    protected $routes = [
        'get' => [
            '/logout/' => 'logout',
        ],
        'post' => [
            '/login/'  => 'login',
        ]
    ];


    /**
     * User logout
     */
    public function LogOut()
    {
        $action = __NAMESPACE__ . '\actions\Logout';
        $this->actionController->executeAction($action);

        $this->standardView('Logout');
    }


    /**
     * User login
     */
    public function LogIn()
    {
        $this->standardAction('Login');
    }
}
