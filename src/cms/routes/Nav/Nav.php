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

namespace cms\routes\Nav;

use \cms\resources\Permissions;

/**
 * RouteController for site navigation editing
 */
class Nav extends \rakelley\jhframe\classes\RouteController
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
            '/index/'   => 'index',
            '/editing/' => 'editing',
        ],
        'post' => [
            '/add/'    => 'add',
            '/edit/'   => 'edit',
            '/delete/' => 'delete',
        ]
    ];
    /**
     * Permission used to authenticate routes
     * @var string
     */
    protected $permission = Permissions::NAV;


    /**
     * Composite view with all nav entries
     */
    public function Index()
    {
        $this->routeAuth();

        $this->standardView('Index');
    }

    /**
     * Editing view for a single entry
     */
    public function Editing()
    {
        $this->routeAuth();

        $arguments = [
            'required' => ['route' => 'default'],
            'method' => 'get',
        ];
        $parameters = $this->getArguments($arguments);

        $this->standardView('EditForm', $parameters);
    }


    /**
     * Add new entry action
     */
    public function Add()
    {
        $this->routeAuth();

        $this->standardAction('Add');
    }

    /**
     * Edit entry action
     */
    public function Edit()
    {
        $this->routeAuth();

        $this->standardAction('Edit');
    }

    /**
     * Delete entry action
     */
    public function Delete()
    {
        $this->routeAuth();

        $this->standardAction('Delete');
    }
}
