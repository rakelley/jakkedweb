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
namespace main\models;

/**
 * Model for articles table
 */
class Article extends \rakelley\jhframe\classes\Model implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\model\DeleteByParameter,
        \rakelley\jhframe\traits\model\GetCount,
        \rakelley\jhframe\traits\model\InsertAutoPrimary,
        \rakelley\jhframe\traits\model\TakesParameters,
        \rakelley\jhframe\traits\model\UpdateByPrimary;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['id', 'date', 'title', 'author', 'tags', 'content'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'id';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$articles
     */
    protected $table = 'articles';


    /**
     * Reverts all articles written by $author to the default author, manual as
     * MySQL doesn't support ON DELETE SET DEFAULT constraints
     *
     * @param  string $author
     * @return void
     */
    public function revertAuthor($author)
    {
        $where = 'author';
        $manualQuery = "UPDATE `$this->table` SET `author`=DEFAULT";
        $this->db->setQuery($manualQuery)
                 ->addWhere()
                 ->Equals($where)
                 ->makeStatement()
                 ->Bind($where, [$where => $author])
                 ->Execute();
    }


    /**
     * Add a new article
     * 
     * @param  array $input
     * @return void
     */
    public function Add(array $input)
    {
        $this->insertAutoPrimary($input);

        $this->resetProperties();
    }


    /**
     * Get a single article by primary key
     * 
     * @return array|null
     */
    protected function getArticle()
    {
        $userTable = 'users';
        $select = array_merge($this->columns, ['fullname', 'profile']);

        $result = $this->db->newQuery('select', $this->table,
                                      ['select' => $select])
                           ->addJoin($userTable)
                           ->On('author', 'username')
                           ->addWhere()
                           ->Equals($this->primary)
                           ->makeStatement()
                           ->Bind($this->primary, $this->parameters)
                           ->Fetch();

        return ($result) ?: null;
    }

    /**
     * Update an article by primary key
     * 
     * @param  array $input
     * @return void
     */
    protected function setArticle($input)
    {
        $this->updateByPrimary($input);
    }

    /**
     * Delete an article by primary key
     * 
     * @return void
     */
    protected function unsetArticle()
    {
        $this->deleteByParameter();
    }


    /**
     * Get all articles or a set delimitted by a starting point and count
     * 
     * @return array|null
     * @todo this should be public with args instead of parameters
     */
    protected function getArticles()
    {
        $userTable = 'users';
        $columns = array_diff($this->columns, ['author']);
        $manualSelect = implode(',', $columns) . ',fullname AS author';

        $this->db->newQuery('select', $this->table, ['select' => $manualSelect])
                 ->stripTicks()
                 ->addJoin($userTable)
                 ->On('author', 'username')
                 ->addOrder(['DESC' => $this->primary]);

        if (isset($this->parameters['pointer']) &&
            isset($this->parameters['results'])
        ) {
            $this->db->addLimit([
                $this->parameters['pointer'],
                $this->parameters['results']
            ]);
        }

        $result = $this->db->makeStatement()
                           ->FetchAll();
        return ($result) ?: null;
    }


    /**
     * Searches articles according to 'query' parameter, a string of one or more
     * terms delimited by underscore.
     * Current hosting is still on MySQL 5.5 so using LIKEs instead of full
     * text search.
     * 
     * @return array
     * @todo this should be public with arg instead of parameter, also possibly
     * break up into submethods
     */
    protected function getResults()
    {
        if (!isset($this->parameters['query'])) {
            throw new \BadMethodCallException('No Query Set', 500);
        }

        $terms = array_map(
            function($term) { return '%' . $term . '%'; },
            explode('_', $this->parameters['query'])
        );
        $placeholders = array_map(
            function($i) { return 'term' . $i; },
            array_keys($terms)
        );

        $userTable = 'users';
        $selectors = array_diff($this->columns, ['author']);
        $manualSelect = implode(',', $selectors) . ',fullname AS author';

        $args = ['select' => $manualSelect, 'distinct' => true];
        $this->db->newQuery('select', $this->table, $args)
                 ->stripTicks()
                 ->addJoin($userTable)
                 ->On('author', 'username');

        $columns = ['tags', 'title', 'content', 'fullname'];
        $firstColumn = true;
        array_walk(
            $columns,
            function($column) use ($placeholders, &$firstColumn) {
                if ($firstColumn) {
                    $operator = null;
                    $firstColumn = false;
                } else {
                    $operator = 'OR';
                }
                $this->db->addWhere($operator)
                         ->Like($column, $placeholders, 'OR');
            }
        );

        $values = array_combine($placeholders, $terms);

        $result = $this->db->addOrder(['DESC' => $this->primary])
                           ->makeStatement()
                           ->Bind($placeholders, $values)
                           ->FetchAll();

        return ($result) ?: null;
    }
}
