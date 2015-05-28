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
 * View for mobile navigation
 */
class MobileNav extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * Store for fetched data
     * @var array
     */
    private $data;
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
                $children = (isset($this->data['children'][$parent['title']])) ?
                            $this->data['children'][$parent['title']] :
                            null;
                return $this->fillTop($parent, $children);
            },
            $this->data['parents']
        ));

        $this->viewContent = <<<HTML
<nav class="mobilenav">
    <a href="" class="mobilenav-opener js-listToggler"
        description="Site Navigation">
        Menu <span class="mobilenav-opener-icon"></span>
    </a>
    <ul class="mobilenav-list">
        {$elements}
    </ul>
</nav>
HTML;
    }


    /**
     * Converts a top-level entry into markup for either a plain entry or a
     * toggler with child entries in dropdown
     * 
     * @param  array      $entry    Top-level entry
     * @param  array|null $children Child entries if any
     * @return string
     */
    private function fillTop($entry, $children=null)
    {
        $parent = $this->fillItem($entry);
        
        if ($children) {
            $arrow = json_decode('"' . '\u25BE' . '"');
            $title = $entry['title'] . ' ' . $arrow;
            $childItems = implode('</li><li>', array_map([$this, 'fillItem'],
                                                         $children));
            $group = <<<HTML
<li class="mobilenav-top-withdropdown js-listToggler">
    {$title}
</li>
<ul class="mobilenav-dropdown">
    <li>{$parent}</li>
    <li>{$childItems}</li>
</ul>

HTML;
        } else {
            $group = '<li class="mobilenav-top">' . $parent . '</li>';
        }

        return $group;
    }


    /**
     * Converts a single entry into anchor form
     * 
     * @param  array  $entry
     * @return string
     */
    private function fillItem(array $entry)
    {
        return <<<HTML
<a href="{$entry['route']}" title="{$entry['description']}">
    {$entry['title']}
</a>
HTML;
    }
}
