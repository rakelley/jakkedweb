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
namespace main\routes\Events\views;

/**
 * View for news widget
 */
class News extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * Number of articles to display
     * @var int
     */
    private $numberOfEntries = 5;
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
        $this->data = array_map(
            function($entry) {
                $entry['content'] =
                    $this->articles->filterNews($entry['content']);
                return $entry;
            },
            $this->articles->getPage(1, $this->numberOfEntries)
        );
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $entries = implode('', array_map([$this, 'fillEntry'], $this->data));

        $this->viewContent = <<<HTML
<h2 class="news-heading">Recent News</h2>
<ul class="news-entries">
    {$entries}
</ul>
<a href="/search/" class="news-searchlink" title="Search Articles" rel="search">
    Read Past Articles
</a>

HTML;
    }


    /**
     * Generates markup for a single article
     * 
     * @param  array  $entry Article properties
     * @return string
     */
    private function fillEntry($entry)
    {
        $date = date('m/d/y', strtotime($entry['date']));

        return <<<HTML
<li class="news-entry">
    <section class="news-entry-heading">
        <a href="/article/{$entry['id']}">
            <h3 class="news-entry-title">{$entry['title']}</h3>
            <p class="news-entry-attribution">{$entry['author']} on {$date}</p>
        </a>
    </section>
    <section class="news-entry-body">{$entry['content']}</section>
    <a class="news-entry-full" href="/article/{$entry['id']}">Read More</a>
</li>
HTML;
    }
}
