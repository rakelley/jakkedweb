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

namespace cms\routes\Quotes\views;

/**
 * FormView for adding a new quote
 */
class AddForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'quote' => [
            'type' => 'text',
            'label' => 'Quote:',
            'attr' => [
                'name' => 'quote',
                'placeholder' => 'Enter Your Quote Here',
            ],
            'required' => true,
            'sanitize' => ['filters' => 'encodeHtml'],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Add Quote',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'quotes/add',
        'method'          => 'post',
        'data-valmethods' => 'reload-hide',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Add A New Quote';
}
