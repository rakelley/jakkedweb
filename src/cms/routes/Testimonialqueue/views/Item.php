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

namespace cms\routes\Testimonialqueue\views;

/**
 * Composite View for a single testimonial
 */
class Item extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IHasSubViews
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\TakesParameters,
        \rakelley\jhframe\traits\view\MakesSubViews;


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $this->viewContent = <<<HTML
<h2 class="page-heading">Editing Queued Testimonial</h2>
{$this->subViews['reject']}
{$this->subViews['approve']}

<script data-main="js/src/cms/main" data-page="lib/mceInit" src="js/require.js">
</script>

HTML;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\traits\view\MakesSubViews::getSubViewList()
     */
    protected function getSubViewList()
    {
        return [
            'approve' => 'ApproveForm',
            'reject' => 'RejectForm',
        ];
    }
}
