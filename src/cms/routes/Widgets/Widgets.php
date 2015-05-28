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

namespace cms\routes\Widgets;

use \cms\resources\Permissions;

/**
 * RouteController for widgets index
 */
class Widgets extends \rakelley\jhframe\classes\RouteController
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
    ];
    /**
     * Permission used to authenticate routes
     * @var string
     */
    protected $permission = Permissions::WIDGETS;


    /**
     * Index view of all widgets
     */
    public function Index()
    {
        $this->routeAuth();

        $this->standardView('Index');
    }
}
