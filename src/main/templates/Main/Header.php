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
 * View for Main template header fragment
 */
class Header extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IHasSubViews
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\MakesSubViews;


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $logo = $this->fillLogo();
        $social = $this->fillSocMediaLinks();

        $this->viewContent = <<<HTML
<header class="template-main-header" role="banner">
    <div class="template-main-header-top">
        {$logo}
        <div class="randquote">
            &quot;%headerQuote%&quot;
        </div>
    </div>

    <div class="template-main-header-bottom" role="navigation">
        {$this->subViews['siteNav']}
        {$this->subViews['mobileNav']}
        {$social}
    </div>
</header>

<header class="template-main-mobile_header" role="banner">
    <div class="template-main-header-top">
        {$logo}
        {$social}
    </div>

    <div class="template-main-header-bottom" role="navigation">
        {$this->subViews['mobileNav']}
    </div>
</header>

HTML;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\traits\view\MakesSubViews::getSubViewList()
     */
    protected function getSubViewList()
    {
        return [
            'siteNav' => 'SiteNav',
            'mobileNav' => 'MobileNav',
        ];
    }


    /**
     * Generates svg logo and wrapper
     * 
     * @return string
     */
    private function fillLogo()
    {
        return <<<HTML
<div class="template-main-header-logo-container">
    <a href="/" class="template-main-header-logo-wrapper" rel="index,home"
        title="Jakked Hardcore Gym - The best strength facility in Illinois">
        <svg class="logo" viewBox="0 0 500 240">
            <text x="10" y="125" class="logo-text-jakked" textLength="475">
                JAKKED
            </text>
            <text x="10" y="212.5" class="logo-text-hardcore" textLength="475">
                HARDCORE
            </text>
            <text x="377" y="170" class="logo-text-gym" letter-spacing="-0.904"
                transform="rotate(-40 402 195)">
                GYM
            </text>
            <circle class="logo-circle-outer" cx="405" cy="128" r="70"/>
            <circle class="logo-circle-inner" cx="405" cy="128" r="76"/>
            <circle class="logo-circle-outer" cx="405" cy="128" r="80"/>
        </svg>
    </a>
</div>

HTML;
    }


    /**
     * Generates social media section
     * 
     * @return string
     */
    private function fillSocMediaLinks()
    {
        return <<<HTML
<section class="template-main-header-socmedia">
    <a href="//jakkedhardcore.com/rss/" rel="alternate" class="ion-social-rss"
        type="application/rss+xml"
        title="Follow Jakked Hardcore Gym via RSS"></a>
    <a href="//www.youtube.com/user/JakkedHardcoreGym" rel="external"
        class="ion-social-youtube-outline"
        title="Jakked Hardcore on YouTube"></a>
    <a href="//twitter.com/JakkedGym" rel="external" class="ion-social-twitter"
        title="Jakked Hardcore on Twitter"></a>
    <a href="//www.facebook.com/pages/Jakked-Hardcore-Gym/208019165557"
        class="ion-social-facebook" rel="external"
        title="Jakked Hardcore on Facebook"></a>
</section>

HTML;
    }
}
