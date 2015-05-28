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
 * Model for pages table
 */
class Page extends \rakelley\jhframe\classes\Model implements
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
    protected $columns = ['route', 'pagegroup', 'description', 'title',
                          'priority'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'route';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'pages';


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
     * Get list of primary keys for all pages belonging to a group
     * 
     * @param  string     $group Group to filter by
     * @return array|null
     */
    public function getGroup($group)
    {
        $where = 'pagegroup';
        $result = $this->db->newQuery('select', $this->table,
                                      ['select' => $this->primary])
                           ->addWhere()
                           ->Equals($where)
                           ->makeStatement()
                           ->Bind($where, [$where => $group])
                           ->FetchAll();

        return ($result) ? array_column($result, $this->primary) : null;
    }


    /**
     * Get list of all pages sorted by priority
     * 
     * @return array|null
     */
    protected function getPages()
    {
        $result = $this->db->newQuery('select', $this->table)
                           ->addOrder(['DESC' => 'priority'])
                           ->makeStatement()
                           ->FetchAll();

        return ($result) ?: null;
    }


    /**
     * Get single row by primary parameter
     * 
     * @return array|null
     */
    protected function getPage()
    {
        return $this->selectOneByParameter();
    }

    /**
     * Update single row by primary parameter
     * 
     * @param  array $input
     * @return void
     */
    protected function setPage($input)
    {
        $this->updateByPrimary($input);
    }

    /**
     * Delete single row by primary parameter
     * 
     * @return void
     */
    protected function unsetPage()
    {
        $this->deleteByParameter();
    }
}
