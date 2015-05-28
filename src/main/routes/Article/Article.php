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
namespace main\routes\Article;

/**
 * RouteController for individual article views
 */
class Article extends \rakelley\jhframe\classes\RouteController
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController::$routes
     */
    protected $routes = [
        'get' => [
            '/\b[\d]+\b/' => 'article'
        ],
    ];


    /**
     * Tries to execute standardView with article matching id
     * 
     * @param  int $id
     * @return void
     */
    public function Article($id)
    {
        $this->standardView('Article', ['id' => $id]);
    }
}
