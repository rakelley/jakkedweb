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
 * View for Main template head fragment
 */
class Head extends \rakelley\jhframe\classes\View
{

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $this->viewContent = <<<HTML
<base href="%basePath%" />
<title>%pageTitle%</title>
<meta charset="utf-8" />
<meta name="keywords" content="gym, powerlifting, strongman, bodybuilding,
    aurora gym, chicago gym, oswego gym, montgomery gym, illinois gym,
    hardcore gym, old school gym, weight lifting, strength competition,
    weight lifting competition, jakked hardcore gym, jakked hardcore" />
<meta name="viewport" content="width=device-width, initial-scale=1,
    maximum-scale=1" />
<meta name="description" content="%pageDescription%" />

<link rel="icon" href="/favicon.ico" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="/rss/" />

<!--[if lte IE 8]>
    <link rel="stylesheet" href="//universal-ie6-css.googlecode.com/files/ie6.1.1.css">
    <p>Internet Explorer 8 or below detected, these browsers are no longer
        supported and are a high security risk.
        Please upgrade to a modern browser such as
        <a href="//www.google.com/chrome/" rel="nofollow">Google Chrome</a>, 
        <a href="//www.mozilla.org/firefox/" rel="nofollow">Mozilla Firefox</a>,
        or IE 9 or higher.
    </p>
<![endif]-->
<!--[if ! lte IE 8]><!-->   
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="/stylesheets/main-layout.css" />
<!--<![endif]-->

<script async><!--
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-3899204-3']);
    _gaq.push(['_trackPageview']);
    
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' :
                 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
--></script>

HTML;
    }
}
