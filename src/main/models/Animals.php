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
 * Model for animals table
 */
class Animals extends \rakelley\jhframe\classes\Model
{
    use \rakelley\jhframe\traits\model\DeleteOnValues,
        \rakelley\jhframe\traits\model\SelectAll;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['number', 'name'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'number';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'animals';


    /**
     * Add one or more new rows
     *
     * @param  array $animals List of row values to add
     * @return void
     */
    public function Add(array $animals)
    {
        $args = ['columns' => $this->columns, 'rows' => count($animals)];

        //flatten multi-level array for binding to placeholders
        $values = [];
        array_walk_recursive(
            $animals,
            function($v) use (&$values) {
                $values[] = $v;
            }
        );

        $this->db->newQuery('insert', $this->table, $args)
                 ->makeStatement()
                 ->Execute($values);
    }


    /**
     * Delete one or more rows
     * 
     * @param  array $animals Keys for rows to delete
     * @return void
     */
    public function Delete(array $animals)
    {
        $this->deleteOnValues($animals);
    }


    /**
     * Get all rows
     * 
     * @return array
     */
    protected function getAnimals()
    {
        return $this->selectAll();
    }
}
