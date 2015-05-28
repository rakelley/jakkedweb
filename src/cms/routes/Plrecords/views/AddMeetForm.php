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

namespace cms\routes\Plrecords\views;

/**
 * FormView for adding a new meet
 */
class AddMeetForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'meet' => [
            'type' => 'text',
            'label' => 'Meet Name:',
            'attr' => [
                'name' => 'meet',
                'placeholder' => '20XX Blah Championships',
            ],
            'required' => true,
            'sanitize' => ['filters' => ['word' => '\s']],
        ],
        'date' => [
            'type' => 'text',
            'label' => 'Meet Date: <small>must be in YYYY-MM-DD format</small>',
            'attr' => [
                'name' => 'date',
                'class' => 'valDate',
                'placeholder' => '20XX-0X-0X',
            ],
            'required' => true,
            'sanitize' => ['filters' => ['date' => 'Y-m-d']],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Add Meet',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'plrecords/addmeet',
        'method'          => 'post',
        'data-valmethods' => 'reload-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = [
        'title' => 'Add A New Meet',
        'sub'   => 'A meet must be added to the system before records from it
                    can be accepted',
    ];
}
