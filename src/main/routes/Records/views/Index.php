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
namespace main\routes\Records\views;

/**
 * View containing index of all current records categories
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IHasMetaData
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\GetsMetaData;

    /**
     * List of current records categories, each must have a view and title
     * @var array
     */
    private $records = [
        [
            'view' => 'powerlifting',
            'title' => 'Gym Powerlifting Records'
        ],
    ];


    function __construct()
    {
        $this->metaRoute = 'records/';
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $records = implode('', array_map([$this, 'fillRecord'],
                                         $this->records));

        $this->viewContent = <<<HTML
<div class="column-row alignment-center">
    {$records}
</div>
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
<section class="column-eight">
    <a href="records/{$record['view']}" class="button-large-smalltext">
        {$record['title']}
    </a>
</section>

HTML;
    }
}
