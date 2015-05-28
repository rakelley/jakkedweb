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
 * CMS navigation view based on user permissions
 */
class Nav extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * AuthService instance
     * @var object
     */
    private $auth;
    /**
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * UserNav repo instance
     * @var object
     */
    private $userNav;


    /**
     * @param \cms\repositories\UserNav                          $userNav
     * @param \rakelley\jhframe\interfaces\services\IAuthService $auth
     */
    function __construct(
        \cms\repositories\UserNav $userNav,
        \rakelley\jhframe\interfaces\services\IAuthService $auth
    ) {
        $this->userNav = $userNav;
        $this->auth = $auth;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $username = $this->auth->getUser('username');
        if ($username) {
            $this->data['name'] = $this->auth->getUser('fullname');
            $this->data['nav'] = $this->userNav->getNav($username);
        }
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        if (!$this->data) {
            $this->viewContent = '<nav class="cms_nav"></nav>';
            return;
        }

        $welcome = 'Welcome, ' . $this->data['name'];
        $menu = $this->fillMenu($this->data['nav']);

        $this->viewContent = <<<HTML
<nav class="cms_nav">
    <section>
        <h4 class="cms_nav-welcome">
            {$welcome}
        </h4>
    </section>

    <section>
        {$menu}
    </section>

    <section class="cms_nav-buttons">
        <a href="/auth/logout">Logout</a>
        <a href="/readme">Help</a>
        <a href="/account/">My Account</a>
    </section>
</nav>

HTML;
    }


    /**
     * Generates markup for navigation buttons
     * 
     * @param  array|null $data Navigation entries to make buttons for
     * @return string
     */
    private function fillMenu($data)
    {
        if (!$data) return '';

        $list = implode('', array_map(
            function($category, $entries) {
                return $this->fillCategory($category, $entries);
            },
            array_keys($data),
            array_values($data)
        ));

        return <<<HTML
<button class="cms_nav-menu-opener js-listToggler" description="Site Navigation">
    Menu <span class="cms_nav-menu-icon"></span>
</button>
<div class="cms_nav-menu">
    {$list}
</div>

HTML;
    }


    /**
     * Generates markup for all entries in a category
     * 
     * @param  string $category Name of category
     * @param  array  $entries   List of entries in category
     * @return string
     */
    private function fillCategory($category, array $entries)
    {
        $category = ucfirst($category);

        $items = implode('', array_map(
            function($entry) {
                return <<<HTML
<a href="{$entry['path']}">{$entry['title']}</a>

HTML;
            },
            $entries
        ));

        return <<<HTML
<button class="cms_nav-toggler js-listToggler">
    {$category} ▾
</button>
<div class="cms_nav-dropdown">
    {$items}
</div>

HTML;
    }
}
