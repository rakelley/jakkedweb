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

namespace cms\routes\Plrecords\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for updating a powerlifting record
 */
class Update extends \rakelley\jhframe\classes\FormAction
{
    /**
     * PlRecords repo instance
     * @var object
     */
    private $records;


    /**
     * @param \cms\routes\Plrecords\views\RecordForm               $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\PlRecords                         $records
     */
    function __construct(
        \cms\routes\Plrecords\views\RecordForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\PlRecords $records
    ) {
        $this->records = $records;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->records->setRecord($this->input);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        $fields = $this->records->getFields();
        array_walk(
            $fields,
            function($values, $field) {
                if (!in_array($this->input[$field], $values)) {
                    throw new InputException(
                        'Invalid Value For Field: ' . $field
                    );
                }
            }
        );
    }
}
