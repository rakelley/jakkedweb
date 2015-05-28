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
 * FormView for approving testimonials
 */
class ApproveForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData
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
        'content' => [
            'type' => 'textarea',
            'attr' => [
                'name' => 'content',
                'class' => 'valTextarea',
                'data-tinymce' => '',
            ],
            'data-binding' => 'content',
            'required' => true,
            'sanitize' => ['filters' => 'tidytext'],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Approve Submission',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'testimonialqueue/approve',
        'method'          => 'post',
        'data-valmethods' => 'redirect-hide',
        'data-redirect'   => 'queues/',
    ];
    /**
     * Testimonialqueue repo instance
     * @var object
     */
    private $queue;


    function __construct(
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder,
        \main\repositories\TestimonialQueue $queue
    ) {
        parent::__construct($builder);
        $this->queue = $queue;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->queue->getTestimonial($this->parameters['id']);
    }
}
