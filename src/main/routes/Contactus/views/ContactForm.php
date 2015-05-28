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
namespace main\routes\Contactus\views;

/**
 * FormView for contact form
 */
class ContactForm extends \rakelley\jhframe\classes\FormView
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\HasBotcheckField;

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
                'placeholder' => 'Enter Your Full Name',
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'email' => [
            'label' => 'Email:',
            'type' => 'email',
            'attr' => [
                'name' => 'email',
                'placeholder' => 'Enter Your Email Address',
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'textarea' => [
            'label' => 'Message:',
            'type' => 'textarea',
            'attr' => [
                'name' => 'textarea',
                'class' => 'valTextarea',
                'placeholder' => "What's On Your Mind?",
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Send Message',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'contactus/contact',
        'method'          => 'post',
        'data-valmethods' => 'show-show',
    ];


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $success = 'Thanks for your message! We will get back to you ASAP!';
        $this->viewContent = $this->standardConstructor($success);
    }
}
