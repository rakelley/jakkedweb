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
 * FormAction for editing a page
 */
class Edit extends \rakelley\jhframe\classes\FormAction
{
    /**
     * FlatPage repo instance
     * @var object
     */
    private $page;


    /**
     * @param \cms\routes\Page\views\EditForm                      $view
     * @param \cms\repositories\FlatPage                           $page
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     */
    function __construct(
        \cms\routes\Page\views\EditForm $view,
        \cms\repositories\FlatPage $page,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator
    ) {
        $this->page = $page;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->page->setPage($this->input);
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

        $this->input['priority'] = round($this->input['priority'], 1);
        if ($this->input['priority'] < 0 || $this->input['priority'] > 1) {
            throw new InputException('Priority must be between 0.0 and 1.0');
        }
    }
}
