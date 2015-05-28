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
 * View for user account password update form
 */
class PasswordForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     * @var array
     */
    protected $fields = [
        'old' => [
            'label' => 'Current Password:',
            'type' => 'password',
            'attr' => [
                'name' => 'old',
                'class' => 'valPassword',
                'placeholder' => 'Existing Password',
            ],
            'required' => true,
            'sanitize' => [
                'minlength' => 8,
            ],
        ],
        'new' => [
            'label' => 'New Password:',
            'type' => 'password',
            'attr' => [
                'name' => 'new',
                'class' => 'valNewPassword',
                'placeholder' => 'New Password',
            ],
            'required' => true,
            'sanitize' => [
                'minlength' => 8,
            ],
        ],
        'confirm' => [
            'label' => 'Confirm New Password:',
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
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Update',
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
        'action'          => 'account/setpassword',
        'method'          => 'post',
        'data-valmethods' => 'show-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     * @var array
     */
    protected $title = [
        'title' => 'Change My Password',
        'sub'   => 'passwords are case sensitive',
    ];


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $success = 'Password Successfully Changed';
        $this->viewContent = $this->standardConstructor($success);
    }
}
