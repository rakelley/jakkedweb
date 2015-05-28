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
 * Model for headerquotes table
 */
class Quotes extends \rakelley\jhframe\classes\Model
{
    use \rakelley\jhframe\traits\model\DeleteOnValues,
        \rakelley\jhframe\traits\model\InsertAutoPrimary,
        \rakelley\jhframe\traits\model\SelectAll;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['id', 'quote'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'id';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'headerquotes';


    /**
     * Add a new quote
     *
     * @param  string $quote
     * @return void
     */
    public function Add($quote)
    {
        $this->insertAutoPrimary(['quote' => $quote]);
    }


    /**
     * Delete one or more quotes by id
     * 
     * @param  array $ids
     * @return void
     */
    public function Delete(array $ids)
    {
        $this->deleteOnValues($ids);
    }


    /**
     * Get all rows
     * 
     * @return array
     */
    protected function getQuotes()
    {
        return $this->selectAll();
    }
}
