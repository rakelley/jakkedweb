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
 * View for user account profile change form
 */
class ProfileForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     * @var array
     */
    protected $fields = [
        'profile' => [
            'type' => 'textarea',
            'attr' => [
                'name' => 'profile',
                'class' => 'valTextarea',
                'data-tinymce' => '',
                'placeholder' => 'Your biography is currently empty',
            ],
            'required' => true,
            'data-binding' => 'profile',
            'sanitize' => ['filters' => 'tidytext'],
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
        'action'          => 'account/setprofile',
        'method'          => 'post',
        'data-valmethods' => 'show-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     * @var array
     */
    protected $title = [
        'title' => 'Change My Biography',
        'sub'   => 'basic HTML is allowed but custom styling will be discarded',
    ];
    /**
     * UserAccount repo instance
     * @var object
     */
    private $user;


    function __construct(
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder,
        \main\repositories\UserAccount $user
    ) {
        parent::__construct($builder);
        $this->user = $user;
    }


    public function fetchData()
    {
        $this->data['profile'] =
            $this->user->getProfile($this->parameters['username']);
    }


    public function constructView()
    {
        $success = 'Profile Successfully Changed';
        $this->viewContent = $this->standardConstructor($success);
    }
}
