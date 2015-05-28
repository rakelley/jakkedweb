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

/**
 * FormAction for adding a new quote
 */
class Add extends \rakelley\jhframe\classes\FormAction
{
    /**
     * Quotes repo instance
     * @var object
     */
    private $quotes;


    /**
     * @param \cms\routes\Quotes\views\AddForm                     $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\Quotes                            $quotes
     */
    function __construct(
        \cms\routes\Quotes\views\AddForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\Quotes $quotes
    ) {
        $this->quotes = $quotes;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->quotes->addQuote($this->input['quote']);
    }
}
