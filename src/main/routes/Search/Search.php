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
namespace main\routes\Search;

/**
 * RouteController for article searches
 */
class Search extends \rakelley\jhframe\classes\RouteController
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
    ];


    /**
     * Search view with optional query and page parameters
     */
    public function Index()
    {
        $arguments = [
            'required' => [
                'page' => 'default',
            ],
            'accepted' => [
                'query' => [
                    'filters' => ['spacetounderscore', 'word', 'strtolower'],
                ],
            ],
            'method' => 'get',
        ];
        $parameters = $this->getArguments($arguments, false);

        $this->standardView('Index', $parameters);
    }
}
