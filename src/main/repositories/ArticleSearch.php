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
namespace main\repositories;

/**
 * Repository for searching articles
 */
class ArticleSearch extends \rakelley\jhframe\classes\Repository
{
    /**
     * Article model instance
     * @var object
     */
    protected $articleModel;
    /**
     * IKeyValCache instance
     * @var object
     */
    protected $cache;
    /**
     * Primary article repo
     * @var object
     */
    protected $indexRepo;


    function __construct(
        Article $indexRepo,
        \rakelley\jhframe\interfaces\services\IKeyValCache $cache,
        \main\models\Article $articleModel
    ) {
        $this->indexRepo = $indexRepo;
        $this->cache = $cache;
        $this->articleModel = $articleModel;
    }


    /**
     * Passthrough to get index page from primary Article repo
     * @see \main\repositories\Article::getPage
     */
    public function getPage($pageNum, $itemsPerPage)
    {
        return $this->indexRepo->getPage($pageNum, $itemsPerPage);
    }


    /**
     * Passthrough to get index pagecount from primary Article repo
     * @see \main\repositories\Article::getPageCount
     */
    public function getPageCount($itemsPerPage)
    {
        return $this->indexRepo->getPageCount($itemsPerPage);
    }


    /**
     * Search articles with optional pagination
     * 
     * @param string   $query   Underscore-delimited term(s)
     * @param int|null $page    Page number if paginating
     * @param int|null $perPage Articles per page if paginating
     */
    public function Search($query, $page=null, $perPage=null)
    {
        $articles = $this->getArticles($query);

        if (!$articles) {
            $result = null;
        } elseif ($page && $perPage) {
            $pointer = ($page - 1) * $perPage;
            $result = [
                'articles' => array_slice($articles, $pointer, $perPage),
                'pagecount' => ceil(count($articles) / $perPage),
            ];
        } else {
            $result = ['articles' => $articles];
        }

        return $result;
    }


    /**
     * Tries to get all articles matching query from cache, if cache misses
     * retrieves from model and writes to cache
     * 
     * @param  string     $query Underscore-delimited term(s)
     * @return array|null
     */
    protected function getArticles($query)
    {
        $cacheKey = 'ArticleSearchResults_' . $query;

        if ($this->cache->Read($cacheKey) !== false) {
            $articles = $this->cache->Read($cacheKey);
        } else {
            $this->articleModel->setParameters(['query' => $query]);
            $articles = $this->articleModel->results;

            if ($articles) {
                $articles = $this->sortSearchResults($articles, $query);
            }
            $this->cache->Write($articles, $cacheKey);
        }

        return $articles;
    }


    /**
     * Performs weighted sort of articles
     * 
     * @param  array  $articles Articles to sort
     * @param  string $query    Underscore-delimited term(s)
     * @return array            Sorted list of articles
     */
    protected function sortSearchResults($articles, $query)
    {
        $terms = array_map(
            function($term) {
                return '\b' . preg_quote($term, '/') . '\b';
            },
            explode('_', $query)
        );

        $articles = array_map(
            function($article) use ($terms) {
                $article['weight'] = $this->getSearchWeight($article, $terms);
                return $article;
            },
            $articles
        );

        usort(
            $articles,
            function($a, $b) {
                return $b['weight'] - $a['weight'];
            }
        );

        array_walk(
            $articles,
            function(&$article) {
                unset($article['weight']);
            }
        );

        return $articles;
    }


    /**
     * Creates weight value for article based on term occurence
     * 
     * @param  array  $article Article to weight
     * @param  array  $terms   List of terms in pregquote form
     * @return int             Weight value
     */
    protected function getSearchWeight(array $article, array $terms)
    {
        $weight = 0;
        array_walk(
            $terms,
            function($term) use ($article, &$weight) {
                $weight += preg_match_all("/$term/i", $article['author']) * 8;
                $weight += preg_match_all("/$term/i", $article['tags']) * 6;
                $weight += preg_match_all("/$term/i", $article['title']) * 4;
                $weight += preg_match_all("/$term/i", $article['content']);
            }
        );

        return $weight;
    }
}
