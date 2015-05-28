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

namespace cms\repositories;

/**
 * Repository for interacting with all user roles as a set
 */
class Roles extends \rakelley\jhframe\classes\Repository
{
    /**
     * Roles model instance
     * @var object
     */
    private $roles;


    function __construct(
        \cms\models\Roles $roles
    ) {
        $this->roles = $roles;
    }


    /**
     * Get a list of all roles with their properties
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->roles->roles;
    }


    /**
     * Get a sorted list of roles which are supersets of other roles
     *
     * @example
     * $relations = [
     *     'foo' => ['bar', 'baz'],
     *     'lorem' => ['ipsum'],
     * ];
     * //Role 'foo' contains at minimum all the permissions of roles 'bar' and
     * //'baz'
     * //Role 'lorem' contains at minimum all the permissions of role 'ipsum'
     * 
     * @return array Keys are role names, values are list of role names which
     *               are related to key
     */
    public function getRelations()
    {
        $roles = $this->getAll();
        $relations = [];
        array_walk(
            $roles,
            function($role) use (&$relations) {
                if (!$role['related']) {
                    return;
                }

                $related = explode(',', $role['related']);
                array_walk(
                    $related,
                    function($relation) use ($role, &$relations) {
                        $relations[$relation][] = $role['role'];
                    }
                );
            }
        );

        return $relations;
    }
}
