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
 * View for Main template footer fragment
 */
class Footer extends \rakelley\jhframe\classes\View
{

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $this->viewContent = <<<HTML
<footer class="template-main-footer">
    <div class="template-main-footer-copyright">
        <p>Jakked Hardcore Gym 2006-%year% &copy; All Rights Reserved &reg;</p>
    </div>
    <div class="template-main-footer-address">
        <p>
            1450 S. East River Road, Unit D<br/>
            Montgomery, IL 60538<br/>
            630.966.8611
        </p>
    </div>
</footer>

<!-- end page_container -->
</div>

<!-- JS -->
<script defer>
(function() {
    if (typeof require !== 'function') {
        var script = document.createElement('script');
        script.src = 'js/require.js';
        script.setAttribute('data-main', 'js/src/main/main');
        document.getElementsByTagName('main')[0].appendChild(script);
    };
}());
</script>
    </body>
</html>

HTML;
    }
}
