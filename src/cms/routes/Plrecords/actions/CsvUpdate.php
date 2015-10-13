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

/**
 * FormAction for updating powerlifting records with a CSV file
 */
class CsvUpdate extends \rakelley\jhframe\classes\FormAction
{
    /**
     * PlRecordCsvValidator instance
     * @var object
     */
    private $csvValidator;


    /**
     * @param \cms\PlRecordCsvValidator                            $csvValidator
     * @param \cms\routes\Plrecords\views\CsvForm                  $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     */
    function __construct(
        \cms\PlRecordCsvValidator $csvValidator,
        \cms\routes\Plrecords\views\CsvForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator
    ) {
        $this->csvValidator = $csvValidator;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->csvValidator->pushValidated();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        $this->csvValidator->validateFile($this->input['file']['tmp_name']);
    }
}
