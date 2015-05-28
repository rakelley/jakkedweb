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
 * View for Alertbanner widget
 */
class Alertbanner extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * Alertbanner repo instance
     * @var object
     */
    private $banner;
    /**
     * Stored for fetched banner data
     * @var array
     */
    private $bannerData = [];


    /**
     * @param \main\repositories\Alertbanner $banner
     */
    function __construct(
        \main\repositories\Alertbanner $banner
    ) {
        $this->banner = $banner;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->bannerData = $this->banner->getBanner();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        if (!$this->bannerData) {
            $this->viewContent = ' ';
            return;
        }

        $this->viewContent = <<<HTML
<div class="alertbanner-wrapper">
    <a href="{$this->bannerData['href']}" class="alertbanner">
        {$this->bannerData['title']}
    </a>
</div>

HTML;
    }
}
