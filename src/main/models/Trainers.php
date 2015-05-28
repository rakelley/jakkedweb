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
 * Model for trainers table
 */
class Trainers extends \rakelley\jhframe\classes\Model implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\model\DeleteByParameter,
        \rakelley\jhframe\traits\model\InsertAll,
        \rakelley\jhframe\traits\model\SelectAll,
        \rakelley\jhframe\traits\model\SelectOneByParameter,
        \rakelley\jhframe\traits\model\TakesParameters,
        \rakelley\jhframe\traits\model\UpdateByPrimary;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['name', 'profile', 'visible'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'name';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'trainers';


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
     * Delete a single row by primary parameter
     * 
     * @param  string $name Primary key value
     * @return void
     */
    public function Delete($name)
    {
        $this->setParameters(['name' => $name]);
        $this->deleteByParameter();
        $this->resetProperties();
    }


    /**
     * Get all rows
     * 
     * @return array|null
     */
    protected function getAllTrainers()
    {
        return $this->selectAll();
    }


    /**
     * Get all rows where column visible is true
     * 
     * @return array|null
     */
    protected function getAllVisible()
    {
        $col = 'visible';
        $result = $this->db->newQuery('select', $this->table)
                           ->addWhere()
                           ->Equals($col)
                           ->makeStatement()
                           ->Bind($col, [$col => 1])
                           ->FetchAll();

        return ($result) ?: null;
    }


    /**
     * Get a single row matching primary parameter
     * 
     * @return array|null
     */
    protected function getTrainer()
    {
        return $this->selectOneByParameter();
    }

    /**
     * Update a single row matching primary parameter
     * 
     * @param  array $input
     * @return void
     */
    protected function setTrainer(array $input)
    {
        $this->updateByPrimary($input);
    }
}
