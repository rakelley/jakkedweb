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

namespace cms\routes\Recovery\views;

/**
 * FormView for initiating account recovery
 */
class InitiateForm extends \rakelley\jhframe\classes\FormView
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'username' => [
            'label' => 'Username:',
            'type' => 'email',
            'attr' => [
                'name' => 'username',
                'class' => 'valExistUsername',
                'placeholder' => 'janedoe@example.com',
            ],
            'required' => true,
            'sanitize' => 'default',
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
                'value' => 'Recover Password',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'recovery/initiate',
        'method'          => 'post',
        'data-valmethods' => 'replace-show',
    ];
}
