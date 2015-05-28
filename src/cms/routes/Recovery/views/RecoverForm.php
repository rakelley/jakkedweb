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
 * FormView for performing account recovery
 */
class RecoverForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'token' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'token',
            ],
            'data-binding' => 'token',
            'required' => true,
            'sanitize' => [],
        ],
        'username' => [
            'label' => 'Username:',
            'type' => 'email',
            'attr' => [
                'name' => 'username',
                'class' => 'valUsername',
                'placeholder' => 'Please confirm your username for security',
            ],
            'required' => true,
            'autofocus' => true,
            'sanitize' => 'default',
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
                'minlength' => 8
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
                'value' => 'Change Password',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'recovery/recover',
        'method'          => 'post',
        'data-valmethods' => 'redirect-show',
        'data-redirect'   => '/',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Recover Your Account';


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $this->viewContent = $this->standardConstructor();
        $this->viewContent .= <<<HTML
<script data-main="js/src/cms/main" data-page="cms/routes/Recovery/recoverform"
src="js/require.js">
</script>

HTML;
    }
}
