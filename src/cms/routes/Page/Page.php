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

namespace cms\routes\Page;

use \cms\resources\Permissions;

/**
 * RouteController for managing static html pages on the main site
 */
class Page extends \rakelley\jhframe\classes\RouteController
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
            '/new/'     => 'newpage',
        ],
        'post' => [
            '/add/'    => 'add',
            '/edit/'   => 'edit',
            '/delete/' => 'delete',
        ],
    ];
    /**
     * Permission to authenticate routes with
     * @var string
     */
    protected $permission = Permissions::PAGES;


    /**
     * Listing of current pages
     */
    public function Index()
    {
        $this->routeAuth();

        $this->standardView('Index');
    }

    /**
     * Editing view for single page
     */
    public function Editing()
    {
        $this->routeAuth();

        $arguments = [
            'required' => ['name' => ['filters' => ['strtolower', 'word']]],
            'method' => 'get',
        ];
        $parameters = $this->getArguments($arguments);
        $this->standardView('Editing', $parameters);
    }

    /**
     * New page creation view
     */
    public function newPage()
    {
        $this->routeAuth();

        $this->standardView('NewForm');
    }


    /**
     * New page creation action
     */
    public function Add()
    {
        $this->routeAuth();

        $this->standardAction('Add');
    }

    /**
     * Editing action for single page
     */
    public function Edit()
    {
        $this->routeAuth();

        $this->standardAction('Edit');
    }

    /**
     * Delete action for single page
     */
    public function Delete()
    {
        $this->routeAuth();

        $this->standardAction('Delete');
    }
}
