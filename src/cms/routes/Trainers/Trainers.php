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

namespace cms\routes\Trainers;

use \cms\resources\Permissions;

/**
 * RouteController for managing trainer profiles
 */
class Trainers extends \rakelley\jhframe\classes\RouteController
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
            '/new/'     => 'newTrainer',
        ],
        'post' => [
            '/add/'    => 'add',
            '/edit/'   => 'edit',
            '/delete/' => 'delete',
            '/setphoto/' => 'setPhoto',
        ],
    ];
    /**
     * Permission used to authenticate routes
     * @var string
     */
    protected $permission = Permissions::PAGES;


    /**
     * Composite view of all trainers
     */
    public function Index()
    {
        $this->routeAuth();

        $this->standardView('Index');
    }

    /**
     * View for editing a trainer
     */
    public function Editing()
    {
        $this->routeAuth();

        $arguments = [
            'required' => ['name' => ['filters' => ['word' => '\s']]],
            'method' => 'get',
        ];
        $parameters = $this->getArguments($arguments);
        $this->standardView('Editing', $parameters);
    }

    /**
     * View for adding a new trainer
     */
    public function newTrainer()
    {
        $this->routeAuth();

        $this->standardView('NewTrainer');
    }


    /**
     * Action for adding a new trainer
     */
    public function Add()
    {
        $this->routeAuth();

        $this->standardAction('Add');
    }

    /**
     * Action for editing an existing trainer
     */
    public function Edit()
    {
        $this->routeAuth();

        $this->standardAction('Edit');
    }

    /**
     * Action for deleting a trainer
     */
    public function Delete()
    {
        $this->routeAuth();

        $this->standardAction('Delete');
    }

    /**
     * Action for setting a trainer's photo
     */
    public function setPhoto()
    {
        $this->routeAuth();

        $this->standardAction('SetPhoto');
    }
}
