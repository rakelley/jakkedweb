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
 * Model for nav table
 */
class Nav extends \rakelley\jhframe\classes\Model implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\model\DeleteByParameter,
        \rakelley\jhframe\traits\model\InsertAll,
        \rakelley\jhframe\traits\model\SelectOneByParameter,
        \rakelley\jhframe\traits\model\TakesParameters,
        \rakelley\jhframe\traits\model\UpdateByPrimary;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['route', 'title', 'parent', 'description'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'route';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'nav';


    /**
     * Add a new row
     * 
     * @param  array $input
     * @return void
     */
    public function Add(array $input)
    {
        $this->insertAll($input);
        $this->resetProperties();
    }


    /**
     * Get all rows
     * 
     * @return array|null
     */
    protected function getEntries()
    {
        $result = $this->db->newQuery('select', $this->table)
                           ->addOrder(['ASC' => ['parent', 'title']])
                           ->makeStatement()
                           ->FetchAll();

        return ($result) ?: null;
    }


    /**
     * Get a single row by primary parameter
     * 
     * @return array
     */
    protected function getEntry()
    {
        return $this->selectOneByParameter();
    }

    /**
     * Update a single row by primary paramter
     * 
     * @param  array $input
     * @return void
     */
    protected function setEntry($input)
    {
        $this->updateByPrimary($input);
    }

    /**
     * Delete a single row by primary parameter
     * 
     * @return void
     */
    protected function unsetEntry()
    {
        $this->deleteByParameter();
    }


    /**
     * Get list of titles of entries which are parents
     * 
     * @return array|null
     */
    protected function getParentList()
    {
        $result = $this->db->newQuery('select', $this->table,
                                      ['select' => 'title'])
                           ->addWhere()
                           ->isNull('parent')
                           ->makeStatement()
                           ->FetchAll();

        return ($result) ? array_column($result, 'title') : null;
    }
}
