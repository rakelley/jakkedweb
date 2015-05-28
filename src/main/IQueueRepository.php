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
namespace main;

/**
 * Interface for repositories which can provide data for standardized queue
 * views.
 */
interface IQueueRepository
{

    /**
     * Returns array of all items in the queue as individual arrays, or null.
     * Defined keys are minimum required, may include others.
     * 
     * @return array Multilevel array, child arrays contain at least
     *     int    'id'   ID number of entry
     *     string 'date' Formatted datestring for entry
     */
    public function getAll();
}
