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
 * View for user account photo setting
 */
class PhotoForm extends \rakelley\jhframe\classes\FormView implements
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
        'MAX_FILE_SIZE' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'MAX_FILE_SIZE',
                'value' => '100000',
            ],
        ],
        'photo' => [
            'label' => 'Choose a picture to upload:',
            'type' => 'file',
            'attr' => [
                'name' => 'photo',
                'class' => 'valImage',
            ],
            'required' => true,
        ],
        'current' => [
            'method' => 'fillCurrent',
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Upload',
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
        'action'          => 'account/setphoto',
        'method'          => 'post',
        'enctype'         => 'multipart/form-data',
        'data-valmethods' => 'reload-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     * @var array
     */
    protected $title = [
        'title' => 'Change My Photo',
        'sub'   => 'must be a jpg, png, or gif, and less than 100kb in size',
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


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data['photo'] =
            $this->user->getPhoto($this->parameters['username']);
    }


    /**
     * Custom field method for filling current photo
     * 
     * @return string
     */
    protected function fillCurrent()
    {
        if (!$this->data['photo']) {
            return '';
        }

        $time = time();
        return <<<HTML
<label>Current Image:
    <img class="image-user" src="/{$this->data['photo']}?{$time}" />
</label>
HTML;
    }
}
