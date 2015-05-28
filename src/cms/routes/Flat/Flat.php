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

namespace cms\routes\Flat;

use \cms\resources\Permissions;

/**
 * RouteController for authorized and unauthorized landing pages and all flat
 * views
 */
class Flat extends \rakelley\jhframe\classes\RouteController
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\controller\Authenticated,
        \rakelley\jhframe\traits\controller\HasFlatViews;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController::$routes
     */
    protected $routes = [
        'get' => [
            '/index/'  => 'index',
            '/[\w]/'   => 'flatView'
        ],
    ];
    /**
     * Permission used to authenticate route access
     * @var string
     */
    protected $permission = Permissions::ALLUSERS;


    /**
     * Try to serve userindex, catching auth failure exception and serving
     * regular index instead
     */
    public function Index()
    {
        try {
            $this->flatView('userindex');
        } catch(\RuntimeException $e) {
            if ($e->getCode() === 403) {
                $this->standardView('Index');
            } else {
                throw $e;
            }
        }
    }


    /**
     * Overrides trait implementation due to needing to auth
     * @see \rakelley\jhframe\traits\controller\HasFlatViews::flatView()
     */
    public function flatView($view)
    {
        $this->routeAuth();

        $this->serveFlatView($view);
    }
}
