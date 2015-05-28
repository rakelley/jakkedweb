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

namespace cms\routes\Gallery;

use \cms\resources\Permissions;

/**
 * RouteController for dealing with galleries
 */
class Gallery extends \rakelley\jhframe\classes\RouteController
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
            '/add/'    => 'add',
            '/delete/' => 'delete',
        ]
    ];
    /**
     * Permission for authenticating routes
     * @var string
     */
    protected $permission = Permissions::GALLERY;


    /**
     * View for add/deleting/viewing galleries
     */
    public function Index()
    {
        $this->routeAuth();

        $this->standardView('Index');
    }


    /**
     * Add gallery action
     */
    public function Add()
    {
        $this->routeAuth();

        $this->standardAction('Add');
    }

    /**
     * Delete gallery action
     */
    public function Delete()
    {
        $this->routeAuth();

        $this->standardAction('Delete');
    }
}
