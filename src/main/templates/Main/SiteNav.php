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
namespace main\templates\Main;

/**
 * View for site navigation
 */
class SiteNav extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * Navigation repo instance
     * @var object
     */
    private $navEntries;


    /**
     * @param \main\repositories\Navigation $navEntries
     */
    function __construct(
        \main\repositories\Navigation $navEntries
    ) {
        $this->navEntries = $navEntries;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->navEntries->getAll();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $elements = implode('', array_map(
            function($parent) {
                if (isset($this->data['children'][$parent['title']])) {
                    $children = $this->data['children'][$parent['title']];
                    return $this->fillGroup($parent, $children);
                } else {
                    return $this->fillSingle($parent);
                }
            },
            $this->data['parents']
        ));

        $this->viewContent = <<<HTML
<nav class="sitenav">
    <ul class="sitenav-list">
        {$elements}
    </ul>
</nav>
HTML;
    }


    /**
     * Generates markup for a top-level entry and its child entries
     * 
     * @param  array  $parent
     * @param  array  $children
     * @return string
     */
    private function fillGroup($parent, $children)
    {
        return $this->fillSingle($parent, true)
             . implode('', array_map([$this, 'fillSingle'], $children))
             . '</ul></li>';
    }


    /**
     * Generates markup for a single entry, with conditional if it's a top-level
     * entry with children
     * 
     * @param  array   $entry
     * @param  boolean $hasChildren
     * @return string
     */
    private function fillSingle($entry, $hasChildren=false)
    {
        $title = $entry['title'] . (($hasChildren) ? ' &#9662;' : '');
        $end = ($hasChildren) ? '<ul class="sitenav-list-dropdown">' : '</li>';

        return <<<HTML
<li>
    <a href="{$entry['route']}" title="{$entry['description']}">
        {$title}
    </a>
{$end}
HTML;
    }
}
