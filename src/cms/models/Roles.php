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
 * Model for roles table
 */
class Roles extends \rakelley\jhframe\classes\Model
{
    use \rakelley\jhframe\traits\model\SelectAll;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['role', 'description', 'related'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'role';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'roles';


    /**
     * Gets all roles
     * 
     * @return array
     */
    protected function getRoles()
    {
        return $this->selectAll();
    }
}
