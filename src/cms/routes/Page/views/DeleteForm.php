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

namespace cms\routes\Page\views;

/**
 * FormView for deleting pages
 */
class DeleteForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'route' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'route',
            ],
            'data-binding' => 'name',
            'required' => true,
            'sanitize' => ['filters' => ['strtolower', 'word']],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Delete',
                'class' => 'button_dangerous',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'page/delete',
        'method'          => 'post',
        'data-valmethods' => 'redirect-hide',
        'data-redirect'   => 'page/',
        'data-confirm'    => '',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Remove this page.  This action cannot be undone.';
}
