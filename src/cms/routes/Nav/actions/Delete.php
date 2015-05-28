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

namespace cms\routes\Nav\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for deleting a nav entry
 */
class Delete extends \rakelley\jhframe\classes\FormAction
{
    /**
     * Navigation repo instance
     * @var object
     */
    private $navEntries;


    /**
     * @param \cms\routes\Nav\views\DeleteForm                     $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\Navigation                        $navEntries
     */
    function __construct(
        \cms\routes\Nav\views\DeleteForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\Navigation $navEntries
    ) {
        $this->navEntries = $navEntries;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->navEntries->deleteEntry($this->input['route']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if (!$this->navEntries->entryExists($this->input['route'])) {
            throw new InputException('Entry Not Found');
        } elseif ($this->navEntries->entryHasChildren($this->input['route'])) {
            throw new InputException(
                'Cannot Delete Top-Level Entry Which Still Has Children'
            );
        }
    }
}
