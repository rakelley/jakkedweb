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
 * Model for alertbanner table
 *
 * This table should normally only have a single row
 */
class Alertbanner extends \rakelley\jhframe\classes\Model
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['href', 'title'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'alertbanner';


    /**
     * Gets banner row if one is set
     * 
     * @return array|null
     */
    protected function getBanner()
    {
        $result = $this->db->newQuery('select', $this->table)
                           ->makeStatement()
                           ->Fetch();

        return ($result) ?: null;
    }


    /**
     * Sets banner row
     * 
     * @param array $input Values for columns
     */
    protected function setBanner($input)
    {
        $this->db->newQuery('update', $this->table,
                            ['columns' => $this->columns])
                 ->makeStatement()
                 ->Bind($this->columns, $input)
                 ->Execute();
    }
}
