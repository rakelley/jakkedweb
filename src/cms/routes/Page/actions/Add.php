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
 * FormAction for creating a new page
 */
class Add extends \rakelley\jhframe\classes\FormAction implements
    \rakelley\jhframe\interfaces\action\IHasResult
{
    use \rakelley\jhframe\traits\ConfigAware;

    /**
     * Application base path from config
     * @var string
     */
    private $basePath;
    /**
     * FlatPage repo instance
     * @var object
     */
    private $page;


    /**
     * @param \cms\routes\Page\views\NewForm                       $view
     * @param \cms\repositories\FlatPage                           $page
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     */
    function __construct(
        \cms\routes\Page\views\NewForm $view,
        \cms\repositories\FlatPage $page,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator
    ) {
        $this->page = $page;

        $this->basePath = $this->getConfig()->Get('APP', 'base_path');

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->page->addPage($this->input);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IHasResult::getResult()
     */
    public function getResult()
    {
        $path = str_replace('cms.', '', $this->basePath) .
                $this->input['route'];

        $result = <<<HTML
<p class="form-status-success">
    Your Page Now Exists At: <a href="{$path}">{$path}</a>
</p>
HTML;

        return $result;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if ($this->page->getPage($this->input['route'])) {
            throw new InputException('Page Already Exists');
        }

        $this->input['priority'] = round($this->input['priority'], 1);
        if ($this->input['priority'] < 0 || $this->input['priority'] > 1) {
            throw new InputException('Priority must be between 0.0 and 1.0');
        }
    }
}
