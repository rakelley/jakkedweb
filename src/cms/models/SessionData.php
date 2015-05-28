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
 * Model for session_data table
 */
class SessionData extends \rakelley\jhframe\classes\Model implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\model\InsertAll,
        \rakelley\jhframe\traits\model\SelectOneByParameter,
        \rakelley\jhframe\traits\model\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['id', 'username', 'expiry', 'ip'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'id';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'session_data';


    /**
     * Gets row matching id
     * 
     * @param  int        $id
     * @return array|null
     */
    public function Get($id)
    {
        $this->setParameters([$this->primary => $id]);

        return $this->selectOneByParameter();
    }


    /**
     * Adds new row
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
     * Removes all rows whose username or id matches an array member
     * 
     * @param  array $keys Value(s) to match against
     * @return void
     */
    public function Remove(array $keys)
    {
        //need to duplicate keys since we have two sets of placeholders to bind
        $values = array_merge($keys, $keys);

        $this->db->newQuery('delete', $this->table)
                 ->addWhere()
                 ->In('id', $keys)
                 ->addWhere('OR')
                 ->In('username', $keys)
                 ->makeStatement()
                 ->Execute($values);

        $this->resetProperties();
    }
}
