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
namespace main\repositories;

/**
 * Repository containing navigation entries for main site
 */
class Navigation extends \rakelley\jhframe\classes\Repository
{
    /**
     * Nav model instance, set by constructor
     * @var object
     */
    protected $navModel;


    function __construct(
        \main\models\Nav $navModel
    ) {
        $this->navModel = $navModel;
    }


    /**
     * Returns whether an entry exists for the provided route
     * 
     * @param  string  $route Key for entry
     * @return boolean
     */
    public function entryExists($route)
    {
        return !!$this->getEntry($route);
    }


    /**
     * Returns true if entry exists and has children
     * 
     * @param  string  $route Key for entry
     * @return boolean
     */
    public function entryHasChildren($route)
    {
        $entry = $this->getEntry($route);
        if (!$entry) {
            return false;
        } else {
            $entries = $this->getAll();
            return in_array($entry['title'], array_keys($entries['children']));
        }
    }


    /**
     * Returns all entries, grouped into parents and children
     * 
     * @return array|null
     */
    public function getAll()
    {
        $entries = $this->navModel->entries;
        if (!$entries) {
            return null;
        }

        $sorted = ['parents' => [], 'children' => []];
        array_walk(
            $entries,
            function($entry) use (&$sorted) {
                if (!$entry['parent']) {
                    $sorted['parents'][] = $entry;
                } else {
                    $sorted['children'][$entry['parent']][] = $entry;
                }
            }
        );

        return $sorted;
    }


    /**
     * Gets entry for provided route
     * 
     * @param  string     $route Key for entry
     * @return array|null
     */
    public function getEntry($route)
    {
        $this->navModel->setParameters(['route' => $route]);
        return $this->navModel->entry;
    }

    /**
     * Updates existing entry
     * 
     * @param  array $input
     * @return void
     */
    public function setEntry(array $input)
    {
        $this->navModel->entry = $input;
    }

    /**
     * Adds new entry
     * 
     * @param  array $input
     * @return void
     */
    public function addEntry(array $input)
    {
        $this->navModel->Add($input);
    }

    /**
     * Deletes entry
     * 
     * @param  string $route Key for entry
     * @return void
     */
    public function deleteEntry($route)
    {
        $this->navModel->setParameters(['route' => $route]);
        unset($this->navModel->entry);
    }


    /**
     * Returns list of titles for top-level entries
     * 
     * @return array
     */
    public function getParentList()
    {
        return $this->navModel->parentList;
    }
}
