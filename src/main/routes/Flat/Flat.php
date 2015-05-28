<?php
/**
 * @package jakkedweb
 * @subpackage main
 * 
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */
namespace main\routes\Flat;

/**
 * RouteController for basic static views
 */
class Flat extends \rakelley\jhframe\classes\RouteController
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\controller\HasFlatViews;

    protected $routes = [
        'get' => [
            '/[\w]/'  => 'flatView'
        ]
    ];
}
