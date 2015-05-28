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

namespace cms\routes\Auth\views;

/**
 * View post-logout
 * 
 * @todo Move js out of inline and replace with something more informative
 */
class Logout extends \rakelley\jhframe\classes\View
{

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $this->viewContent = <<<HTML
<h1 class="page-heading">You Have Been Logged Out</h1>
<p class="page-subheading">
    You will be redirected to the login page in a few seconds
</p>

<script async><!--
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function () {
        var dest = location.origin || location.protocol + "//" + location.host;
        window.location = dest;
    }, 5000);
});
--></script>

HTML;
    }
}
