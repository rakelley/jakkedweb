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
 * Model for accountrecovery table
 */
class AccountRecovery extends \rakelley\jhframe\classes\Model implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\model\DeleteByParameter,
        \rakelley\jhframe\traits\model\InsertAll,
        \rakelley\jhframe\traits\model\SelectOneByParameter,
        \rakelley\jhframe\traits\model\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['token', 'username', 'expiration'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'username';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'accountrecovery';


    /**
     * Add new row
     * 
     * @param  array $input Values for all columns
     * @return void
     */
    public function Add($input)
    {
        // Prevents duplicates
        $this->Delete($input[$this->primary]);

        $this->insertAll($input);
    }


    /**
     * Delete all rows matching primary parameter
     * 
     * @param  string $username
     * @return void
     */
    public function Delete($username)
    {
        $this->setParameters([$this->primary => $username]);
        $this->deleteByParameter();

        $this->resetProperties();
    }


    /**
     * Get row matching primary parameter
     * 
     * @return array
     */
    protected function getEntry()
    {
        return $this->selectOneByParameter();
    }
}
