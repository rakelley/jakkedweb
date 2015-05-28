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
 * Model for testimonials table
 */
class Testimonials extends \rakelley\jhframe\classes\Model implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\model\DeleteByParameter,
        \rakelley\jhframe\traits\model\InsertAutoPrimary,
        \rakelley\jhframe\traits\model\SelectAll,
        \rakelley\jhframe\traits\model\SelectOneByParameter,
        \rakelley\jhframe\traits\model\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$columns
     */
    protected $columns = ['id', 'content', 'date'];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$primary
     */
    protected $primary = 'id';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Model::$table
     */
    protected $table = 'testimonials';


    /**
     * Add a new row
     * 
     * @param  array $input
     * @return void
     */
    public function Add(array $input)
    {
        $this->insertAutoPrimary($input);

        $this->resetProperties();
    }


    /**
     * Get all rows
     * 
     * @return array|null
     */
    protected function getTestimonials()
    {
        return $this->selectAll();
    }


    /**
     * Get one row by primary parameter
     * 
     * @return array|null
     */
    protected function getTestimonial()
    {
        return $this->selectOneByParameter();
    }

    /**
     * Delete one row by primary parameter
     * 
     * @return void
     */
    protected function unsetTestimonial()
    {
        $this->deleteByParameter();
    }
}
