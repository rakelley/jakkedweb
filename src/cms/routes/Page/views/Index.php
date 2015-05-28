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

namespace cms\routes\Page\views;

/**
 * Composite view showing all current pages
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * FlatPage repository instance
     * @var object
     */
    private $pages;


    /**
     * @param \cms\repositories\FlatPage $pages
     */
    function __construct(
        \cms\repositories\FlatPage $pages
    ) {
        $this->pages = $pages;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->pages->getAll();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $pages = implode('', array_map([$this, 'fillPage'], $this->data));

        $this->viewContent = <<<HTML
<h2 class="page-heading">Edit An Existing Static Page</h2>
<p class="page-subheading">If a page not listed here needs to be updated,
    please contact an admin.
</p>                  

<table>
    <thead>
        <tr>
            <th>Edit Page</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        {$pages}
    </tbody>
</table>

HTML;
    }


    /**
     * Generates markup for a single page row
     * 
     * @param  string $page Name of page
     * @return string
     */
    private function fillPage($page)
    {
        return <<<HTML
<tr>
    <td data-th="Edit Page">
        <a class="button" href="/page/editing?name={$page}">Edit</a>
    </td>
    <td data-th="Name">{$page}</td>
</tr>
HTML;
    }
}
