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
 * Model for user_roles table
 */
class UserRoles extends \rakelley\jhframe\classes\Model implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\model\DeleteByParameter,
        \rakelley\jhframe\traits\model\TakesParameters;

    protected $columns = ['username', 'role'];
    protected $primary = ['username', 'role'];
    protected $table = 'user_roles';


    /**
     * Returns all users who have a role that includes $permission
     * 
     * @param  string $permission
     * @return array
     */
    public function getUsersByPermission($permission)
    {
        $permissionsTable = 'role_permissions';
        $where = 'permission';

        $result = $this->db->newQuery('select', $this->table,
                                      ['select' => 'username'])
                           ->addJoin($permissionsTable)
                           ->Using('role')
                           ->addWhere()
                           ->Equals($where)
                           ->makeStatement()
                           ->Bind($where, [$where => $permission])
                           ->FetchAll();

        return ($result) ? array_column($result, 'username') : null;
    }


    /**
     * Gets all roles for a user
     * 
     * @return array|null
     * @todo should use public arg instead of parameter
     */
    protected function getRoles()
    {
        $where = 'username';

        $result = $this->db->newQuery('select', $this->table)
                           ->addWhere()
                           ->Equals($where)
                           ->makeStatement()
                           ->Bind($where, $this->parameters)
                           ->FetchAll();

        return ($result) ? array_column($result, 'role') : null;
    }

    /**
     * Sets roles for a user
     * 
     * @param  array $roles List of roles to assign, can be empty
     * @return void
     */
    protected function setRoles(array $roles)
    {
        $this->__unset('roles');
        if (!$roles) {
            return;
        }

        $username = $this->parameters['username'];
        $values = [];
        array_walk(
            $roles,
            function($role) use ($username, &$values) {
                $values[] = $username;
                $values[] = $role;
            }
        );

        $args = ['columns' => $this->columns, 'rows' => count($roles)];
        $this->db->newQuery('insert', $this->table, $args)
                 ->makeStatement()
                 ->Execute($values);
    }

    /**
     * Deletes all roles for a user
     * 
     * @return void
     */
    protected function unsetRoles()
    {
        $this->deleteByParameter('username');
    }


    /**
     * Gets all permissions for a user
     * 
     * @return array|null
     */
    protected function getPermissions()
    {
        $permissionsTable = 'role_permissions';

        $where = 'username';
        $result = $this->db->newQuery('select', $this->table,
                                      ['select' => 'permission'])
                           ->addJoin($permissionsTable)
                           ->Using('role')
                           ->addWhere()
                           ->Equals($where)
                           ->makeStatement()
                           ->Bind($where, $this->parameters)
                           ->FetchAll();

        return ($result) ? array_column($result, 'permission') : null;
    }
}
