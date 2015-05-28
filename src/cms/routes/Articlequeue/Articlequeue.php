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

namespace cms\routes\Articlequeue;

use \cms\resources\Permissions;

/**
 * RouteController for managing the article queue
 */
class Articlequeue extends \rakelley\jhframe\classes\RouteController
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
            '/item/'  => 'item',
        ],
        'post' => [
            '/approve/' => 'approve',
            '/reject/'  => 'reject',
        ],
    ];
    /**
     * Permission for authenticating routes
     * @var string
     */
    protected $permission = Permissions::QUEUES;


    /**
     * View for queued article
     */
    public function Item()
    {
        $this->routeAuth();

        $arguments = [
            'required' => ['id' => 'default'],
            'method' => 'get'
        ];
        $parameters = $this->getArguments($arguments);

        $this->standardView('Item', $parameters);
    }


    /**
     * Action for approving article
     */
    public function Approve()
    {
        $this->routeAuth();

        $this->standardAction('Approve');
    }

    /**
     * Action for rejecting article
     */
    public function Reject()
    {
        $this->routeAuth();

        $this->standardAction('Reject');
    }
}
