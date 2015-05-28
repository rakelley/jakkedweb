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

namespace cms\routes\Trainers\views;

/**
 * FormView for adding a new trainer
 */
class AddForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'name' => [
            'label' => 'Name: ',
            'type' => 'text',
            'attr' => [
                'name' => 'name',
                'class' => 'valName',
            ],
            'required' => true,
            'sanitize' => [
                'filters' => ['word' => '\s'],
                'maxlength' => 50,
            ],
        ],
        'visible' => [
            'label' => 'Visibility: ',
            'type' => 'select',
            'attr' => [
                'name' => 'visible',
            ],
            'options' => ['Not Visible','Visible'],
            'required' => true,
            'sanitize' => ['filters' => ['word' => '\s']],
        ],
        'profile' => [
            'label' => 'Trainer Profile: <small>HTML is allowed but do not use
                        style tags or inline css.</small>',
            'type' => 'textarea',
            'attr' => [
                'name' => 'profile',
                'class' => 'valTextarea',
                'data-tinymce' => '',
            ],
            'required' => true,
            'sanitize' => ['filters' => 'tidytext'],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Add Trainer',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'trainers/add',
        'method'          => 'post',
        'data-valmethods' => 'redirect-show',
        'data-redirect'   => 'trainers/',
    ];
}
