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

namespace cms\templates\Cms;

/**
 * Default template for CMS app
 */
class Cms implements \rakelley\jhframe\interfaces\ITemplate
{
    use \rakelley\jhframe\traits\ConfigAware,
        \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\MakesSubViews;

    /**
     * CMS base url
     * @var string
     */
    private $basePath;


    function __construct()
    {
        $this->basePath = $this->getConfig()->Get('APP', 'base_path');
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\ITemplate::makeComposite()
     * Note at this time the CMS does not generate nor use any metadata
     */
    public function makeComposite($mainContent, array $metaData=null)
    {
        $this->makeSubViews();

        $head = $this->fillHead();
        $nav = $this->subViews['nav'];

        return <<<HTML
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    {$head}
</head>

<body>
    <div class="page_container">
        <header class="cms_nav-wrapper" role="banner">
            {$nav}
        </header>

        <main class="requires-js" role="main">
            {$mainContent}
        <!-- End Main Content -->
        </main>

        <noscript>Javascript must be enabled to use this CMS.</noscript>
    <!-- End Container -->
    </div>
    <div class="ajax_overlay"></div>

    <script defer>
    (function() {
        if (typeof require !== 'function') {
            var script = document.createElement('script');
            script.src = 'js/require.js';
            script.setAttribute('data-main', 'js/src/cms/main');
            document.getElementsByTagName('main')[0].appendChild(script);
        };
    }());
    </script>

</body>
</html>

HTML;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\traits\view\MakesSubViews::getSubViewList()
     */
    protected function getSubViewList()
    {
        return [
            'nav' => 'Nav',
        ];
    }


    /**
     * Generates markup for head block
     * 
     * @return string
     */
    private function fillHead()
    {
        return <<<HTML
<base href="{$this->basePath}" />

<title>Jakked Hardcore Gym CMS</title>
<meta charset="utf-8">
<meta name="viewport"
    content="width=device-width, initial-scale=1, maximum-scale=1">

<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans">
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Droid+Serif">
<link rel="stylesheet" href="stylesheets/cms-layout.css" />

HTML;
    }
}
