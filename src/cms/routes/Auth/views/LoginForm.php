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

namespace cms\routes\Auth\views;

/**
 * FormView for logging in
 */
class LoginForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'username' => [
            'label' => 'E-mail Address: ',
            'type' => 'email',
            'attr' => [
                'name' => 'username',
                'class' => 'valUsername',
            ],
            'required' => true,
            'autofocus' => true,
            'sanitize' => 'default',
        ],
        'password' => [
            'label' => 'Password: <small>passwords are case sensitive</small>',
            'type' => 'password',
            'attr' => [
                'name' => 'password',
                'placeholder' => 'Password',
            ],
            'required' => true,
            'sanitize' => [],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Log In',
                'class' => 'button',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'auth/login',
        'method'          => 'post',
        'data-valmethods' => 'reload-show',
    ];

}
