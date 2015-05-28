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

namespace cms\routes\Nav\views;

/**
 * FormView for deleting navigation entries
 */
class DeleteForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'route' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'route',
                'value' => '%route%'
            ],
            'required' => true,
            'sanitize' => 'default',
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
        'class'           => 'form-invisible',
        'action'          => 'nav/delete',
        'method'          => 'post',
        'data-valmethods' => 'reload-hide',
        'data-confirm'    => '',
    ];
}
