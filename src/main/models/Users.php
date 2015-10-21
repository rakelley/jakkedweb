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
 * Model for users table
 */
class Users extends \rakelley\jhframe\classes\Model implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\model\DeleteByParameter,
        \rakelley\jhframe\traits\model\SelectOneByParameter,
        \rakelley\jhframe\traits\model\TakesParameters,
        \rakelley\jhframe\traits\model\UpdateSingleByPrimaryParameter;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['username', 'fullname', 'password', 'lastlogin',
                          'profile'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'username';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'users';


    /**
     * Add a new user
     * 
     * @param  array $input
     * @return void
     */
    public function Add(array $input)
    {
        $columns = ['username', 'fullname', 'password'];
        $this->db->newQuery('insert', $this->table, ['columns' => $columns])
                 ->makeStatement()
                 ->Bind($columns, $input)
                 ->Execute();

        $this->resetProperties();
    }


    /**
     * Delete a user by primary key parameter
     *
     * @return void
     */
    public function Delete()
    {
        $this->deleteByParameter();
        $this->resetProperties();
    }


    /**
     * Get list of all users
     * 
     * @return array|null
     */
    protected function getAllUsers()
    {
        $args = ['select' => ['username', 'fullname', 'lastlogin']];
        $result = $this->db->newQuery('select', $this->table, $args)
                           ->addOrder(['DESC' => 'lastlogin'])
                           ->makeStatement()
                           ->FetchAll();

        return ($result) ?: null;
    }


    /**
     * Get fullname for a user
     * 
     * @return string|null
     */
    protected function getFullname()
    {
        $user = $this->__get('user');

        return ($user) ? $user['fullname'] : null;
    }


    /**
     * Get lastlogin for a user
     * 
     * @return string|null
     */
    protected function getLastlogin()
    {
        $user = $this->__get('user');

        return ($user) ? $user['lastlogin'] : null;
    }

    /**
     * Set fullname for a user
     *
     * @param  string $newValue DateTime string
     * @return void
     */
    protected function setLastlogin($newValue)
    {
        $this->updateSingleByPrimaryParameter('lastlogin', $newValue);
    }


    /**
     * Get hashed password for a user
     * 
     * @return string|null
     */
    protected function getPassword()
    {
        $user = $this->__get('user');

        return ($user) ? $user['password'] : null;
    }

    /**
     * Set password for a user
     *
     * @param  string $newValue Hash
     * @return void
     */
    protected function setPassword($newValue)
    {
        $this->updateSingleByPrimaryParameter('password', $newValue);
    }


    /**
     * Get profile for a user
     * 
     * @return string|null
     */
    protected function getProfile()
    {
        $user = $this->__get('user');

        return ($user) ? $user['profile'] : null;
    }

    /**
     * Set profile for a user
     *
     * @param  string $newValue
     * @return void
     */
    protected function setProfile($newValue)
    {
        $this->updateSingleByPrimaryParameter('profile', $newValue);
    }


    /**
     * Fetch a user by primary key parameter
     * 
     * @return array|null
     */
    protected function getUser()
    {
        return $this->selectOneByParameter();
    }
}
