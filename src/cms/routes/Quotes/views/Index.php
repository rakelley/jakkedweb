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

namespace cms\routes\Quotes\views;

/**
 * Composite view with forms for adding/deleting quotes
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IHasSubViews
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\MakesSubViews;


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $this->viewContent = <<<HTML
<h2 class="page-heading">Editing Header Quotes</h2>
{$this->subViews['delete']}
{$this->subViews['add']}

HTML;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\traits\view\MakesSubViews::getSubViewList()
     */
    protected function getSubViewList()
    {
        return [
            'add' => 'AddForm',
            'delete' => 'DeleteForm',
        ];
    }
}
