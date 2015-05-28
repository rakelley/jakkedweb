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

namespace cms\routes\Article;

use \cms\resources\Permissions;

/**
 * RouteController for article editing and deleting
 */
class Article extends \rakelley\jhframe\classes\RouteController
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
            '/editing/' => 'editing',
            '/index/'   => 'index',
        ],
        'post' => [
            '/delete/' => 'delete',
            '/edit/'   => 'edit',
        ],
    ];
    /**
     * Permission to use in routh authentication
     * @var string
     */
    protected $permission = Permissions::ARTICLE_EDITOR;


    /**
     * Article editing view
     */
    public function Editing()
    {
        $this->routeAuth();

        $arguments = [
            'required' => ['id' => 'default'],
            'method' => 'get',
        ];
        $parameters = $this->getArguments($arguments);

        $this->standardView('Editing', $parameters);
    }

    /**
     * Articles list view
     */
    public function Index()
    {
        $this->routeAuth();

        $arguments = [
            'required' => ['page' => 'default'],
            'method' => 'get',
        ];
        $parameters = $this->getArguments($arguments);

        $this->standardView('Index', $parameters);
    }


    /**
     * Delete article action
     */
    public function Delete()
    {
        $this->routeAuth();

        $this->standardAction('Delete');
    }

    /**
     * Edit article action
     */
    public function Edit()
    {
        $this->routeAuth();

        $this->standardAction('Edit');
    }
}
