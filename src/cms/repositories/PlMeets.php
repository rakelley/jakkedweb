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
 * Repository for powerlifting meets
 */
class PlMeets extends \rakelley\jhframe\classes\Repository
{
    /**
     * Meets model instance
     * @var object
     */
    private $meets;


    function __construct(
        \cms\models\PlMeets $meets
    ) {
        $this->meets = $meets;
    }


    /**
     * Get a list of all meets
     * 
     * @return array|null
     */
    public function getAll()
    {
        return $this->meets->meets;
    }


    /**
     * Add a new meet
     * 
     * @param  array $input Meet properties
     * @return void
     */
    public function addMeet(array $input)
    {
        $this->meets->Add($input);
    }


    /**
     * Delete one or more meets
     * 
     * @param  array|string $meets String meet key or list of same
     * @return void
     */
    public function Delete($meets)
    {
        $this->meets->Delete((array) $meets);
    }
}
