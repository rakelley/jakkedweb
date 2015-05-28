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

namespace cms\routes\Page\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for deleting a page
 */
class Delete extends \rakelley\jhframe\classes\FormAction
{
    /**
     * Flag for whether page has a nav entry to be deleted
     * @var boolean
     */
    private $hasNavEntry = false;
    /**
     * Navigation repo instance
     * @var object
     */
    private $navEntries;
    /**
     * FlatPage repo instance
     * @var object
     */
    private $page;


    /**
     * @param \cms\routes\Page\views\DeleteForm                    $view
     * @param \cms\repositories\FlatPage                           $page
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\Navigation                        $navEntries
     */
    function __construct(
        \cms\routes\Page\views\DeleteForm $view,
        \cms\repositories\FlatPage $page,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\Navigation $navEntries
    ) {
        $this->page = $page;
        $this->navEntries = $navEntries;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->page->deletePage($this->input['route']);

        if ($this->hasNavEntry) {
            $this->navEntries->deleteEntry($this->input['route']);
        }
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if (!$this->page->getPage($this->input['route'])) {
            throw new InputException('Page Not Found');
        }

        if ($this->navEntries->entryExists($this->input['route'])) {
            $this->hasNavEntry = true;

            if ($this->navEntries->entryHasChildren($this->input['route'])) {
                throw new InputException(
                    'Cannot Delete Top-Level Entry Which Still Has Children'
                );
            }
        }
    }
}
