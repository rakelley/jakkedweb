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
 * View for video tab widget
 */
class YoutubeTabs extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * Youtube repo instance
     * @var object
     */
    private $yt;


    /**
     * @param \main\repositories\Youtube $yt
     */
    function __construct(
        \main\repositories\Youtube $yt
    ) {
        $this->yt = $yt;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->yt->getRecentVideos();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        if (!$this->data) {
            $tabs = '<p>No Recent Videos</p>';
        } else {
            $tabs = $this->fillTabs($this->data);
        }

        $this->viewContent = $tabs;
    }


    /**
     * Converts list of video ids into markup with tabbed iframes
     * 
     * @param  array  $ids List of video ids
     * @return string
     */
    private function fillTabs(array $ids)
    {
        $tabsItems = [];
        $contentItems = [];

        array_walk(
            $ids,
            function ($id, $i) use (&$tabsItems, &$contentItems) {
                $active = ($i === 0) ? 'active' : '';
                $tabsItems[] = <<<HTML
<li>
    <a class="{$active} ion-social-youtube-outline" href="#video{$i}"></a>
</li>

HTML;
                $contentItems[] = <<<HTML
<li class="{$active} video-wrapper" id="video{$i}">
    <iframe src="//www.youtube.com/embed/{$id}" frameborder="0" allowfullscreen>
    </iframe>
</li>

HTML;
            }
        );

        $tabsBlock = implode('', $tabsItems);
        $contentBlock = implode('', $contentItems);
        $tabs = <<<HTML
<ul class="tabs">
    {$tabsBlock}
</ul>
<ul class="tabs-content">
    {$contentBlock}
</ul>

HTML;

        return $tabs;
    }
}
