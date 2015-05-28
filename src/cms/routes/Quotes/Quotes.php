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

namespace cms\routes\Quotes;

use \cms\resources\Permissions;

/**
 * RouteController for managing quotes
 */
class Quotes extends \rakelley\jhframe\classes\RouteController
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
            '/add/'    => 'add',
            '/delete/' => 'delete',
        ]
    ];
    /**
     * Permission used to authenticate routes
     * @var string
     */
    protected $permission = Permissions::WIDGETS;


    /**
     * Quote management view
     */
    public function Index()
    {
        $this->routeAuth();

        $this->standardView('Index');
    }


    /**
     * Action to add a new quote
     */
    public function Add()
    {
        $this->routeAuth();

        $this->standardAction('Add');
    }

    /**
     * Action to delete one or more quotes
     */
    public function Delete()
    {
        $this->routeAuth();

        $this->standardAction('Delete');
    }
}
