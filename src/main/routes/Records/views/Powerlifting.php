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
 * Powerlifting record view, includes query subview if query args passed by
 * controller
 */
class Powerlifting extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IHasMetaData,
    \rakelley\jhframe\interfaces\view\IHasSubViews
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\TakesParameters,
        \rakelley\jhframe\traits\view\GetsMetaData,
        \rakelley\jhframe\traits\view\MakesSubViews;



    function __construct()
    {
        $this->metaRoute = 'records/powerlifting';
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $records = (isset($this->subViews['records'])) ?
                   $this->subViews['records'] : '';

        $this->viewContent = <<<HTML
<div class="column-sixteen">
    <h1 class="heading-page margin-bottom-none">
        Unofficial Gym Powerlifting Records
    </h1>
    <p>All Numbers in Kilos</p>
</div>

<section class="column-sixteen" data-ajaxtarget>
    {$records}
</section>

{$this->subViews['form']}

HTML;
    }

 
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\traits\view\MakesSubViews::getSubViewList()
     */
    protected function getSubViewList()
    {
        $list = [
            'form' => 'PlForm',
        ];

        if ($this->parameters) {
            $list['records'] = 'PlQuery';
        }

        return $list;
    }
}
