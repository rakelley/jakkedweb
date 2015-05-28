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
namespace main\routes\Testimonials\views;

/**
 * FormView for adding new testimonials
 */
class AddForm extends \rakelley\jhframe\classes\FormView
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\HasBotcheckField;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'testimonials/add',
        'method'          => 'post',
        'data-valmethods' => 'show-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'botcheck' => [
            'method' => 'addBotcheckField',
        ],
        'name' => [
            'label' => 'Name:',
            'type' => 'text',
            'attr' => [
                'name' => 'name',
                'class' => 'valName',
                'placeholder' => 'Your Name',
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'textarea' => [
            'label' => 'Testimonial:',
            'type' => 'textarea',
            'attr' => [
                'name' => 'textarea',
                'class' => 'valTextarea',
                'placeholder' => "Spread the Word!",
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Spread It',
            ],
        ],
    ];


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $success = <<<MSG
Thanks for speaking out!  We'll review your submission and approve it soon.
MSG;
        $this->viewContent = $this->standardConstructor($success);
    }
}
