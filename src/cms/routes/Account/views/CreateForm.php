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

namespace cms\routes\Account\views;

/**
 * View for user account creation form
 */
class CreateForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     * @var array
     */
    protected $fields = [
        'name' => [
            'label' => 'First and Last Name:',
            'type' => 'text',
            'attr' => [
                'name' => 'name',
                'class' => 'valFullname',
                'placeholder' => 'Jane Doe',
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'username' => [
            'label' => 'Email: <small>This will be your username</small>',
            'type' => 'email',
            'attr' => [
                'name' => 'username',
                'class' => 'valNewUsername',
                'placeholder' => 'janedoe@example.com',
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'new' => [
            'label' => 'Password:',
            'type' => 'password',
            'attr' => [
                'name' => 'new',
                'class' => 'valNewPassword',
                'placeholder' => 'New Password',
            ],
            'required' => true,
            'sanitize' => [
                'minlength' => 8
            ],
        ],
        'confirm' => [
            'label' => 'Confirm Password:',
            'type' => 'password',
            'attr' => [
                'name' => 'confirm',
                'class' => 'valConfirmPassword',
                'placeholder' => 'Confirm New Password',
            ],
            'required' => true,
            'sanitize' => [
                'minlength' => 8,
                'equalto' => 'new',
            ],
        ],
        'spamcheck' => [
            'label' =>
                'Verification Question: <small>Name one of the gym dogs</small>',
            'type' => 'text',
            'attr' => [
                'name' => 'spamcheck',
                'class' => 'valSpamcheck',
                'placeholder' => 'I am not a spammer',
            ],
            'required' => true,
            'sanitize' => [
                'filters' => ['word', 'strtolower'],
            ],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Register',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     * @var array
     */
    protected $attributes = [
        'action'          => 'account/create',
        'method'          => 'post',
        'data-valmethods' => 'replace-show',
    ];
}
