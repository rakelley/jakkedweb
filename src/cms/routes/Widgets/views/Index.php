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

namespace cms\routes\Widgets\views;

/**
 * Directory View providing links to all widgets
 */
class Index extends \rakelley\jhframe\classes\View
{
    /**
     * List of widgets to populate
     * @var array
     */
    private $widgets = [
        [
            'route' => 'alertbanner/',
            'title' => 'Alert Banner',
            'description' => 'The flagged link at the top left of each page,
                              intended for special announcements and visitor
                              call-to-actions',
        ],
        [
            'route' => 'quotes/',
            'title' => 'Header Quotes',
            'description' => 'Quotes randomly displayed at the top of each page',
        ],
        [
            'route' => 'animals/',
            'title' => 'Shelter Animal Gallery',
            'description' => 'Gallery of Animals From Aurora Animal Control
                              Shelter',
        ],
    ];


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $list = implode('', array_map([$this, 'fillWidget'], $this->widgets));

        $this->viewContent = <<<HTML
<h2 class="page-heading">Manage Site Widgets</h2>

<section class="column-sixteen">
    {$list}
</section>
HTML;
    }


    /**
     * Generates markup for a widget
     * 
     * @param  array  $widget Widget properties
     * @return string
     */
    private function fillWidget(array $widget)
    {
        return <<<HTML
<a href="{$widget['route']}" class="button">{$widget['title']}</a>
<p class="margin-bottom-double">{$widget['description']}</p>
HTML;
    }
}
