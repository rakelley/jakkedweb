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
 * Model for cmsnav table
 */
class CmsNav extends \rakelley\jhframe\classes\Model
{
    protected $columns = ['permission', 'title', 'category', 'path'];
    protected $primary = 'path';
    protected $table = 'cmsnav';


    /**
     * Gets all nav entries for a user based on their assigned roles
     * 
     * @param  string     $username
     * @return array|null
     */
    public function getNav($username)
    {
        $rolesTable = 'role_permissions';
        $userRolesTable = 'user_roles';

        $manual = <<<SQL
SELECT {$this->table}.*
FROM {$this->table}
JOIN {$rolesTable} USING (permission)
JOIN {$userRolesTable} ON {$rolesTable}.role = {$userRolesTable}.role
WHERE {$userRolesTable}.username=:username
ORDER BY `category`,`title` ASC
SQL;

        $result = $this->db->setQuery($manual)
                           ->makeStatement()
                           ->Bind('username', ['username' => $username])
                           ->FetchAll();

        return ($result) ?: null;
    }
}
