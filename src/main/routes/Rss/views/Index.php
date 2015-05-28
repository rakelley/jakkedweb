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
namespace main\routes\Rss\views;

/**
 * View for article rss feed
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\View::$contentType
     */
    protected $contentType = 'xml';
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
     * Filter service instance
     * @var object
     */
    private $filter;


    /**
     * @param \rakelley\jhframe\interfaces\services\IFilter $filter
     * @param \main\repositories\Article                    $articles
     */
    function __construct(
        \rakelley\jhframe\interfaces\services\IFilter $filter,
        \main\repositories\Article $articles
    ) {
        $this->filter = $filter;
        $this->articles = $articles;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->articles->getAll();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $year = date('Y');
        $posts = implode('', array_map([$this, 'fillPost'], $this->data));

        $this->viewContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
    <rss version="2.0">
        <channel>
            <title>Jakked Hardcore Gym News and Articles</title>
            <link>https://jakkedhardcore.com</link>
            <description>The latest news, updates, and information from Jakked
                Hardcore Gym in Montgomery, IL, the premiere strength facility
                in Illinois.
            </description>
            <language>en-us</language>
            <copyright>Copyright 2006-{$year} Jakked Hardcore Gym</copyright>
            {$posts}
        </channel>
    </rss>
XML;
    }


    /**
     * Generates markup for a single article
     * 
     * @param  array $post Article properties
     * @return string
     */
    private function fillPost($post)
    {
            $content = $this->filter->tidyText($post['content']);
            $content = $this->filter->encodeHtml($content);
            $date = date(DATE_RSS, strtotime($post['date']));

            return <<<XML
<item>
    <title>{$post['title']}</title>
    <link>https://jakkedhardcore.com</link>
    <guid>https://jakkedhardcore.com/article/{$post['id']}</guid>
    <description>{$content}</description>
    <pubDate>{$date}</pubDate>
</item>
XML;
    }
}
