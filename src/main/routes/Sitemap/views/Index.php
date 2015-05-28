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
namespace main\routes\Sitemap\views;

/**
 * Sitemap view
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    use \rakelley\jhframe\traits\ConfigAware;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\View::$contentType
     */
    protected $contentType = 'xml';
    /**
     * Application base url
     * @var string
     */
    private $basePath;
    /**
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * PageData repo instance
     * @var object
     */
    private $pages;


    /**
     * @param \main\repositories\PageData $pages
     */
    function __construct(
        \main\repositories\PageData $pages
    ) {
        $this->pages = $pages;

        $this->basePath = $this->getConfig()->Get('APP', 'base_path');
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $pages = $this->pages->getAll();

        // correct route for home page
        $indexKey = array_search('index', array_column($pages, 'route'));
        $pages[$indexKey]['route'] = '';

        $this->data = $pages;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $pages = implode('', array_map([$this, 'fillPage'], $this->data));

        $this->viewContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {$pages}
</urlset>
XML;
    }


    /**
     * Generates markup for a single page
     * 
     * @param  array  $page Page properties
     * @return string
     */
    private function fillPage(array $page)
    {
        $frequency = ($page['route'] === '') ? 'daily' : 'monthly';

        return <<<XML
<url>
    <loc>{$this->basePath}{$page['route']}</loc>
    <changefreq>{$frequency}</changefreq>
    <priority>{$page['priority']}</priority>
</url>
XML;
    }
}
