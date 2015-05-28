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

namespace cms\routes\Articlequeue\views;

/**
 * FormView for editing/approving a queued article
 */
class EditForm extends \cms\routes\Article\views\EditForm
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'articlequeue/approve',
        'method'          => 'post',
        'data-valmethods' => 'redirect-hide',
        'data-redirect'   => 'queues/',
    ];


    /**
     * @param \cms\repositories\ArticleQueue                     $queue
     * @param \rakelley\jhframe\interfaces\services\IFormBuilder $builder
     */
    function __construct(
        \cms\repositories\ArticleQueue $queue,
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder
    ) {
        $this->alterInheritedFields();

        parent::__construct($builder, $queue);
    }


    /**
     * Makes needed changes to parent field list
     * 
     * @return void
     */
    private function alterInheritedFields()
    {
        $this->fields['submit']['attr']['value'] = 'Approve Article';
        unset($this->fields['date']);
    }
}
