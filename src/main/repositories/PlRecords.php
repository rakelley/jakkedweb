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
 * Repository for Powerlifting Records
 */
class PlRecords extends \rakelley\jhframe\classes\Repository
{
    /**
     * Powerlifting Records model instance
     * @var object
     */
    protected $recordModel;


    function __construct(
        \main\models\PlRecords $recordModel
    ) {
        $this->recordModel = $recordModel;
    }


    /**
     * Returns list of all filterable record fields and all their possible
     * values
     * 
     * @return array
     */
    public function getFields()
    {
        return $this->recordModel->fields;
    }


    /**
     * Returns subset of field values that match all currently stored records
     * 
     * @return array
     */
    public function getIndex()
    {
        return $this->recordModel->index;
    }


    /**
     * Returns all records matching provided parameters
     * 
     * @param  array      $parameters Field parameters
     * @return array|null
     */
    public function getQuery(array $parameters)
    {
        $this->recordModel->setParameters($parameters);
        return $this->recordModel->query;
    }


    /**
     * Returns first record that matches provided parameters
     * 
     * @param  array      $parameters Field parameters
     * @return array|null
     */
    public function getRecord(array $parameters)
    {
        $this->recordModel->setParameters($parameters);
        return $this->recordModel->record;
    }

    /**
     * Sets record for row matching provided parameters
     * 
     * @param  array $input Field parameters and record value
     * @return void
     */
    public function setRecord(array $input)
    {
        $this->recordModel->record = $input;
    }
}
