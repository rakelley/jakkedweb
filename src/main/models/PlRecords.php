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
 * Model for pl_records table
 */
class PlRecords extends \rakelley\jhframe\classes\Model implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\model\InsertAll,
        \rakelley\jhframe\traits\model\SelectOneByParameter,
        \rakelley\jhframe\traits\model\TakesParameters,
        \rakelley\jhframe\traits\model\UpdateByPrimary;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['gender', 'division', 'gear', 'class', 'lift', 'name',
                          'record', 'meet'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = ['gender', 'division', 'gear', 'class', 'lift'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'pl_records';


    /**
     * Returns list of distinct values present in this table for each primary
     * index column
     * 
     * @return array
     */
    protected function getIndex()
    {
        $manualQuery = 'SELECT ' . implode(', ', array_map(
            function($col) {
                return "(SELECT group_concat(DISTINCT `$col`) FROM " .
                       "`$this->table`) AS `$col`";
            },
            $this->primary
        ));

        $result = $this->db->setQuery($manualQuery)
                           ->makeStatement()
                           ->Fetch();

        return array_map(
            function ($list) {
                $items = explode(',', $list);
                asort($items);
                return array_values($items);
            },
            $result
        );
    }


    /**
     * Returns set of rows matching provided one or more parameters
     * 
     * @return array|null
     */
    protected function getQuery()
    {
        $where = array_values(array_intersect($this->primary,
                                              array_keys($this->parameters)));

        $result = $this->db->newQuery('select', $this->table)
                           ->addJoin('pl_meet')
                           ->Using('meet')
                           ->addWhere()
                           ->Equals($where, 'AND')
                           ->addOrder(['ASC' => $where, 'DESC' => 'record'])
                           ->makeStatement()
                           ->Bind($where, $this->parameters)
                           ->FetchAll();

        return ($result) ?: null;
    }


    /**
     * Gets all permitted primary column values from their data tables
     * 
     * @return array
     */
    protected function getFields()
    {
        $manualQuery = "SELECT " . implode(', ', array_map(
            function($field) {
                $table = 'pl_' . $field;
                return "(SELECT group_concat($field) FROM $table) AS $field";
            },
            array_merge($this->primary, ['meet'])
        ));
        
        $result = $this->db->setQuery($manualQuery)
                           ->makeStatement()
                           ->Fetch();

        return array_map(
            function ($list) {
                $items = explode(',', $list);
                asort($items);
                return array_values($items);
            },
            $result
        );
    }


    /**
     * Gets single row that matches full primary key
     * 
     * @return array|null
     */
    protected function getRecord()
    {
        return $this->selectOneByParameter();
    }

    /**
     * Updates single row that matches primary key if the record value is higher
     * than existing record value if any.
     * 
     * Noop doesn't raise an error because we want to invisibly handle not
     * overwriting higher records in order to save user effort.
     */
    protected function setRecord($input)
    {
        $this->setParameters($input);
        $existing = $this->__get('record');

        if (!$existing) {
            $this->insertAll($input);
        } elseif ($existing['record'] < $input['record']) {
            $this->updateByPrimary($input);
        }
    }
}
