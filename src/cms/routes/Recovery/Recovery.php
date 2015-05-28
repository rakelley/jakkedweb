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

namespace cms\routes\Recovery;

/**
 * RouteController for user account recovery
 */
class Recovery extends \rakelley\jhframe\classes\RouteController
{
    use \rakelley\jhframe\traits\controller\AcceptsArguments;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController::$routes
     */
    protected $routes = [
        'get' => [
            '/index/' => 'index',
        ],
        'post' => [
            '/recover/'  => 'recover',
            '/initiate/' => 'initiate',
        ]
    ];


    /**
     * View with recovery form
     */
    public function Index()
    {
        $arguments = [
            'required' => ['token' => []],
            'method' => 'get',
        ];
        $parameters = $this->getArguments($arguments);

        $this->standardView('RecoverForm', $parameters);
    }


    /**
     * Recovery action
     */
    public function Recover()
    {
        $this->standardAction('Recover');
    }

    /**
     * Token creation action
     */
    public function Initiate()
    {
        $this->standardAction('Initiate');
    }
}
