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
 * Default template for Main app
 */
class Main implements \rakelley\jhframe\interfaces\ITemplate
{
    use \rakelley\jhframe\traits\ConfigAware,
        \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\InterpolatesPlaceholders,
        \rakelley\jhframe\traits\view\MakesSubViews;

    /**
     * IKeyValCache service instance
     * @var object
     */
    private $cache;
    /**
     * MetaData for page currently being composited
     * @var array|null
     */
    private $metaData;
    /**
     * Quotes repository instance
     * @var object
     */
    private $quoteRepo;


    /**
     * @param \rakelley\jhframe\interfaces\services\IKeyValCache $cache
     * @param \main\repositories\Quotes                          $quoteRepo
     */
    function __construct(
        \rakelley\jhframe\interfaces\services\IKeyValCache $cache,
        \main\repositories\Quotes $quoteRepo
    ) {
        $this->cache = $cache;
        $this->quoteRepo = $quoteRepo;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\ITemplate::makeComposite()
     */
    public function makeComposite($mainContent, array $metaData=null)
    {
        $this->metaData = $metaData;
        $this->getSubViews();
        $placeholders = $this->preparePlaceholders();

        $composite = <<<HTML
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    {$this->subViews['head']}
</head>

<body>
    <div class="page_container">
        {$this->subViews['header']}

        <main role="main">
            {$this->subViews['banner']}

            {$mainContent}

            <div class="ajax_overlay"></div>
        </main>

        {$this->subViews['footer']}

HTML;

        return $this->interpolatePlaceholders($composite, $placeholders);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\traits\view\MakesSubViews::getSubViewList()
     */
    protected function getSubViewList()
    {
        return [
            'head' => 'Head',
            'header' => 'Header',
            'banner' => 'Alertbanner',
            'footer' => 'Footer',
        ];
    }


    /**
     * Tries to get subviews from cache, failing that makes them using trait
     * 
     * @return void
     */
    private function getSubViews()
    {
        $cacheKey = 'Template_Main_SubViews';

        if ($this->cache->Read($cacheKey)) {
            $this->subViews = $this->cache->Read($cacheKey);
        } else {
            $this->makeSubViews();
            $this->cache->Write($this->subViews, $cacheKey);
        }
    }


    /**
     * Prepares placeholder values for interpolation
     * 
     * @return array
     */
    private function preparePlaceholders()
    {
        $placeholders = [
            //base URL, found in Head
            'basePath' => $this->getConfig()->Get('APP', 'base_path'),
            //page title, found in Head
            'pageTitle' => $this->fillTitle(),
            //page description, found in Head
            'pageDescription' => $this->fillDescription(),
            //random quote, found in Header
            'headerQuote' => $this->quoteRepo->getRandom(1)[0],
            //current year, found in Footer
            'year' => date("Y"),
        ];

        return $placeholders;
    }


    /**
     * Generates page title
     * 
     * @return string
     */
    private function fillTitle()
    {
        $baseTitle = 'Jakked Gym, Old-School Powerlifting &amp; Strongman';
        $pageTitle = (!empty($this->metaData['title'])) ?
                     $this->metaData['title'] : '';

        if (strlen($pageTitle) > 30) {
            $title = $pageTitle;
        } elseif (strlen($pageTitle) > 0) {
            $title = $pageTitle . ' - ' . $baseTitle;
        } else {
            $title = $baseTitle;
        }

        return $title;
    }


    /**
     * Generates page description
     * 
     * @return string
     */
    private function fillDescription()
    {
        if (!empty($this->metaData['description'])) {
            $desc = $this->metaData['description'];
        } else {
            $desc = 'Jakked Hardcore Gym, Old-School Powerlifting &amp; ' .
                    'Strongman in Illinois';
        }

        return $desc;
    }
}
