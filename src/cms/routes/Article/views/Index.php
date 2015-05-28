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

namespace cms\routes\Article\views;

/**
 * View for paginated list of all articles
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * Article repo instance
     * @var object
     */
    private $articles;
    /**
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * Number of articles to display per page
     * @var integer
     */
    private $perPage = 25;


    /**
     * @param \main\repositories\Article $articles
     */
    function __construct(
        \main\repositories\Article $articles
    ) {
        $this->articles = $articles;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data['articles'] =
            $this->articles->getPage($this->parameters['page'], $this->perPage);
        $this->data['pagecount'] =
            $this->articles->getPageCount($this->perPage);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        if (!$this->data['articles']) {
            $articles = '<tr><td colspan="4">No Articles Found</td></tr>';
        } else {
            $articles = implode('', array_map([$this, 'fillArticle'],
                                               $this->data['articles']));
        }
        $nav = $this->fillNav($this->parameters['page'],
                              $this->data['pagecount']);

        $this->viewContent = <<<HTML
<h2 class="page-heading">Edit an Existing Article</h2>

<table>
    <thead>
        <tr>
            <th>Edit</th><th>Date</th><th>Author</th><th>Title</th>
        </tr>
    </thead>
    <tbody>
        {$articles}
    </tbody>
</table>

<div>
    {$nav}
</div>

HTML;
    }


    /**
     * Generates markup for a single article row
     * 
     * @param  array  $article Article properties
     * @return string
     */
    private function fillArticle(array $article)
    {
        $title = substr($article['title'], 0, 80);
        return <<<HTML
<tr>
    <td data-th="Edit">
        <a class="button" href="/article/editing?id={$article['id']}">
            Edit
        </a>
    </td>
    <td data-th="Date">{$article['date']}</td>
    <td data-th="Author">{$article['author']}</td>
    <td data-th="Title">{$title}</td>
</tr>
HTML;
    }


    /**
     * Generates markup for pagination nav
     * 
     * @param  int    $page  Current page
     * @param  int    $count Total number of pages
     * @return string
     */
    private function fillNav($page, $count)
    {
        $prev = '&lt;&lt;&lt; Previous';
        if ($page > 1) {
            $prev = '<a href="/article/index/' . ($page - 1) . '">' . $prev
                  . '</a>';
        }

        $next = 'Next &gt;&gt;&gt;';
        if ($page < $count) {
            $next = '<a href="/article/index/' . ($page + 1) . '">' . $next
                  . '</a>';
        }

        return $prev . ' | ' . $next;
    }
}
