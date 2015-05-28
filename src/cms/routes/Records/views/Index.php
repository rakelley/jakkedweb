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

namespace cms\routes\Records\views;

/**
 * View display links to all record categories
 */
class Index extends \rakelley\jhframe\classes\View
{
    /**
     * List of record categories, each must have route and title
     * @var array
     */
    private $records = [
        ['route' => 'plrecords/', 'title' => 'Gym Powerlifting Records'],
    ];


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $list = implode('', array_map([$this, 'fillRecord'], $this->records));

        $this->viewContent = <<<HTML
<h2 class="page-heading">Select Record Category</h2>

<ul class="plain-list">
    {$list}
</ul>

HTML;
    }


    /**
     * Generates markup for a single record category
     * 
     * @param  array  $record Category properties
     * @return string
     */
    private function fillRecord(array $record)
    {
        return <<<HTML
<li>
    <a class="button" href="{$record['route']}">
        {$record['title']}
    </a>
</li>
HTML;
    }
}
