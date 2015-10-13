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

namespace cms\routes\Plrecords;

use \cms\resources\Permissions;

/**
 * RouteController for managing powerlifting records
 */
class Plrecords extends \rakelley\jhframe\classes\RouteController
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
            '/addmeet/'   => 'addmeet',
            '/csvupdate/' => 'csvupdate',
            '/update/'    => 'update',
        ]
    ];
    /**
     * Permission used to authenticate routes
     * @var string
     */
    protected $permission = Permissions::RECORDS;


    /**
     * View for all record operations
     */
    public function Index()
    {
        $this->routeAuth();

        $this->standardView('Index');
    }


    /**
     * Action to add meet
     */
    public function addMeet()
    {
        $this->routeAuth();

        $this->standardAction('AddMeet');
    }

    /**
     * Action to update records via csv file
     */
    public function CsvUpdate()
    {
        $this->routeAuth();

        $this->standardAction('CsvUpdate');
    }

    /**
     * Action to update single record
     */
    public function Update()
    {
        $this->routeAuth();

        $this->standardAction('Update');
    }
}
