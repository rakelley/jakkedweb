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

namespace cms\routes\Nav\views;

/**
 * Composite view showing current navigation entries and forms
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData,
    \rakelley\jhframe\interfaces\view\IHasSubViews
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\InterpolatesPlaceholders,
        \rakelley\jhframe\traits\view\MakesSubViews;

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
        $lists = $this->fillLists($this->data['parents'],
                                  $this->data['children']);

        $rows = implode('', array_merge(
            array_map([$this, 'fillRow'], $this->data['parents']),
            array_map(
                function($group) {
                    return implode('', array_map([$this, 'fillRow'], $group));
                },
                $this->data['children']
            )
        ));

        $this->viewContent = <<<HTML
<h2 class="page-heading">Edit Site Navigation</h2>

<section class="column-three margin-bottom">
    <h4 class="alignment-center">Current Layout</h4>
    <ul>
        {$lists}
    </ul>
</section>

<section class="column-thirteen margin-bottom">
    <h4 class="alignment-center">Entries</h4>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Route</th>
                <th>Parent</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            {$rows}
        </tbody>
    </table>
</section>

<section class="column-sixteen">
    <h4 class="alignment-center">New Entry</h4>
    {$this->subViews['addForm']}
</section>

HTML;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\traits\view\MakesSubViews::getSubViewList()
     */
    protected function getSubViewList()
    {
        return [
            'addForm' => 'AddForm',
            'deleteForm' => 'DeleteForm',
        ];
    }


    /**
     * Generates markup for simple list of current entries, with children
     * sorted with parents
     * 
     * @param  array  $parents  List of parent entries
     * @param  array  $children List of child entries
     * @return string           Generated list-items
     */
    private function fillLists($parents, $children)
    {
        return implode('', array_map(
            function($parent) use ($children) {
                if (!isset($children[$parent['title']])) {
                    $childItems = '';
                } else {
                    $childItems = implode('', array_map(
                        function($child) {
                            return '<li>' . $child['title'] . '</li>';
                        },
                        $children[$parent['title']]
                    ));
                    $childItems = '<ul>' . $childItems . '</ul>';
                }

                return <<<HTML
<li>
    {$parent['title']}
    {$childItems}
</li>
HTML;
            },
            $parents
        ));
    }


    /**
     * Generates markup for single entry with details and edit and delete links
     * 
     * @param  array  $entry Entry details
     * @return string        Generated table row
     */
    private function fillRow($entry)
    {
        $parent = ($entry['parent']) ?: 'None';
        $safeRoute = rawurlencode($entry['route']);
        $delete = $this->interpolatePlaceholders($this->subViews['deleteForm'],
                                                 ['route' => $entry['route']]);

        return <<<HTML
<tr>
    <td data-th="Title">{$entry['title']}</td>
    <td data-th="Route">/{$entry['route']}</td>
    <td data-th="Parent">{$parent}</td>
    <td data-th="Edit">
        <a class="button" href="/nav/editing?route={$safeRoute}">
            Edit
        </a>
    </td>
    <td data-th="Delete">{$delete}</td>
</tr>
HTML;
    }
}
