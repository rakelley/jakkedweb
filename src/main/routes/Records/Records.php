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
namespace main\routes\Records;

/**
 * RouteController for lifting records views
 */
class Records extends \rakelley\jhframe\classes\RouteController
{
    use \rakelley\jhframe\traits\controller\AcceptsArguments;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController::$routes
     */
    protected $routes = [
        'get' => [
            '/index/'        => 'index',
            '/powerlifting/' => 'powerlifting',
            '/plquery/'      => 'plquery',
        ],
    ];
    /**
     * Argument list for powerlifting views
     * @var array
     */
    private $plArguments = [
        'required' => [
            'gender' => ['filters' => 'word'],
        ],
        'accepted' => [
            'division' => ['filters' => ['word' => '\s+-']],
            'gear'     => ['filters' => 'word'],
            'class'    => ['filters' => ['word' => '.']],
            'lift'     => ['filters' => 'word'],
        ],
        'method' => 'get',
    ];


    /**
     * Index of all record types
     */
    public function Index()
    {
        $this->standardView('Index');
    }


    /**
     * Powerlifting record view
     */
    public function Powerlifting()
    {
        $parameters = $this->getArguments($this->plArguments, false);

        $this->standardView('Powerlifting', $parameters);
    }


    /**
     * Powerlifting query view
     */
    public function plQuery()
    {
        $parameters = $this->getArguments($this->plArguments);

        $this->standardView('PlQuery', $parameters);
    }
}
