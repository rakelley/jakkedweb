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
namespace main\routes\Search\views;

/**
 * View for article searches
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData,
    \rakelley\jhframe\interfaces\view\IHasMetaData
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\TakesParameters,
        \rakelley\jhframe\traits\view\GetsMetaData;

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
     * Number of results to display per page
     * @var int
     */
    private $perPage = 10;


    /**
     * @param \main\repositories\ArticleSearch $articles
     */
    function __construct(
        \main\repositories\ArticleSearch $articles
    ) {
        $this->articles = $articles;

        $this->metaRoute = 'search/';
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        if (isset($this->parameters['query'])) {
            $this->data = $this->articles->Search($this->parameters['query'],
                                                  $this->parameters['page'],
                                                  $this->perPage);
        } else {
            $this->data['articles'] =
                $this->articles->getPage($this->parameters['page'],
                                         $this->perPage);
            $this->data['pagecount'] =
                $this->articles->getPageCount($this->perPage);
        }
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $formValue = (isset($this->parameters['query'])) ?
                     str_replace('_', ' ', $this->parameters['query']) : '';

        if (!isset($this->data['articles'])) {
            $nav = '';
            $results = '<h2 class="heading-section">No Articles Found, Please
                       Try Another Search</h2>';
        } else {
            $nav = $this->fillSearchNav($this->parameters['page'],
                                        $this->data['pagecount']);
            $results = $this->fillResults($this->data['articles']);
        }

        $this->viewContent = <<<HTML
<div class="column-sixteen">
    <h1 class="heading-page">Jakked Articles</h1>
</div>

<section class="article_search">
    <form class="article_search-form" action="search/" method="get">
        <fieldset>
            <label for="query">Search Jakked Articles:</label>
            <input type="text" name="query" class="required"
                placeholder="Search Jakked Articles" value="{$formValue}" />
            <input type="submit" value="Search" />
        </fieldset>
    </form>

    {$nav}

    <ul class="article_search-results">
        {$results}
    </ul>

    {$nav}
</section>

HTML;
    }


    /**
     * Generates markup for intra-result navigation element
     * 
     * @param  int    $page  Current page
     * @param  int    $count Number of pages
     * @return string
     */
    private function fillSearchNav($page, $count)
    {
        if ($count <= 5) {
            $toFill = range(1, $count);
        } else {
            $toFill = [1, $page - 1, $page, $count];
            if ($page < $count - 1) $toFill[] = $page + 1;
            $toFill = array_filter(array_unique($toFill));
            sort($toFill);
        }
        $items = array_map([$this, 'fillPageLI'], $toFill);

        $spacer = '<li class="article_search-nav-spacer">-</li>';
        if ($count > 5 && $page > 3) {
            $items[0] .= $spacer;
        }
        if ($count > 5 && $page < $count - 2) {
            $items[count($items) - 1] = $spacer . $items[count($items) - 1];
        }

        $items = implode('', $items);

        return <<<HTML
<nav class="article_search-nav">
    <h3 class="article_search-nav-heading">Pages:</h3>
    <ul class="article_search-nav-pagenums">
        {$items}
    </ul>
</nav>
HTML;
    }


    /**
     * Generates markup for a single navigation li element
     * 
     * @param  int    $page Page number element is for
     * @return string
     */
    private function fillPageLI($page)
    {
        $query = (isset($this->parameters['query'])) ?
                 'query=' . $this->parameters['query'] . '&' : '';

        return <<<HTML
<li class="article_search-nav-page">
    <a href="/search/?{$query}page={$page}">{$page}</a>
</li>
HTML;
    }


    /**
     * Generates markup for article results
     * 
     * @param  array  $articles Articles to be listed
     * @return string
     */
    private function fillResults(array $articles)
    {
        return implode('', array_map(
            function($i, $article) {
                $i = $i + 1 + ($this->parameters['page'] - 1) * $this->perPage;
                $blurb = strip_tags($article['content'], '<p>');
                $cutoff = (strpos($blurb, '</p>') < 300) ? 
                          strpos($blurb, '</p>') : 300;
                $blurb = substr($blurb, 0, $cutoff) . '...';
                $date = date('m/d/y', strtotime($article['date']));

                return <<<HTML
<li>
    <a href="/article/{$article['id']}">
        <h2 class="article_search-results-title">{$i}.  {$article['title']}</h2>
        <p class="para-footnote">{$article['author']} on {$date}</p>
        <p>{$blurb}</p>
    </a>
</li>
HTML;
            },
            array_keys($articles),
            array_values($articles)
        ));
    }
}
