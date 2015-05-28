<?php
/**
 * @package jakkedweb
 * @subpackage cms
 * 
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */

namespace cms\models;

/**
 * Model for pl_meet table
 */
class PlMeets extends \rakelley\jhframe\classes\Model
{
    use \rakelley\jhframe\traits\model\DeleteOnValues,
        \rakelley\jhframe\traits\model\InsertAll,
        \rakelley\jhframe\traits\model\SelectAll;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['meet', 'date'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'meet';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'pl_meet';


    /**
     * Add a meet
     * 
     * @param  array $input Values for columns
     * @return void
     */
    public function Add(array $input)
    {
        $this->insertAll($input);
        $this->resetProperties();
    }


    /**
     * Delete one or more meets
     * 
     * @param  array $meets Keys for rows to delete
     * @return void
     */
    public function Delete(array $meets)
    {
        $this->deleteOnValues($meets);
        $this->resetProperties();
    }


    /**
     * Get all meets
     * 
     * @return array|null
     */
    protected function getMeets()
    {
        $result = $this->selectAll();
        return ($result) ? array_column($result, $this->primary) : null;
    }
}
