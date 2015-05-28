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

namespace cms\routes\Quotes\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for deleting one or more quotes
 */
class Delete extends \rakelley\jhframe\classes\FormAction
{
    /**
     * Input service instance
     * @var object
     */
    private $inputLib;
    /**
     * Quotes repo instance
     * @var object
     */
    private $quotes;


    /**
     * @param \cms\routes\Quotes\views\DeleteForm                  $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \rakelley\jhframe\interfaces\services\IInput         $inputLib
     * @param \main\repositories\Quotes                            $quotes
     */
    function __construct(
        \cms\routes\Quotes\views\DeleteForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \rakelley\jhframe\interfaces\services\IInput $inputLib,
        \main\repositories\Quotes $quotes
    ) {
        $this->inputLib = $inputLib;
        $this->quotes = $quotes;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->quotes->deleteQuotes($this->input);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        $pattern = '/quote\d+/';
        $method = 'post';
        $rules = ['filters' => 'int'];

        $input = $this->inputLib->searchKeys($pattern, $method, $rules);

        if (!$input) {
            throw new InputException('No Quotes Submitted');
        } else {
            $this->input = array_values($input);
        }
    }
}
