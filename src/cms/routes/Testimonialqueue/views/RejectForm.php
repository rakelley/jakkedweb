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
 * FormView for rejecting a queued testimonial
 */
class RejectForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'id' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'id',
            ],
            'data-binding' => 'id',
            'required' => true,
            'sanitize' => 'default',
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Reject Submission',
                'class' => 'button_dangerous',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'testimonialqueue/reject',
        'method'          => 'post',
        'data-valmethods' => 'redirect-hide',
        'data-redirect'   => 'queues/',
        'data-confirm'    => '',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Rejections Cannot Be Undone';
}
