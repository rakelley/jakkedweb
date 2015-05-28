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

namespace cms\routes\Trainers\views;

/**
 * FormView for changing a trainer's photo
 */
class PhotoForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'MAX_FILE_SIZE' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'MAX_FILE_SIZE',
                'value' => '100000',
            ],
        ],
        'name' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'name',
            ],
            'data-binding' => 'name',
            'required' => true,
            'sanitize' => ['filters' => ['word' => '\s']],
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
     */
    protected $attributes = [
        'action'          => 'trainers/setphoto',
        'method'          => 'post',
        'enctype'         => 'multipart/form-data',
        'data-valmethods' => 'reload-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = [
        'title' => 'Change Trainer Photo',
        'sub'   => 'must be a jpg, png, or gif, and less than 100kb in size',
    ];
    /**
     * Trainers repo instance
     * @var object
     */
    private $trainers;


    function __construct(
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder,
        \main\repositories\Trainers $trainers
    ) {
        $this->trainers = $trainers;

        parent::__construct($builder);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->trainers->getPhoto($this->parameters['name']);
    }


    /**
     * Custom method for 'current' field
     * 
     * @return string
     */
    protected function fillCurrent()
    {
        if (!$this->data) {
            return '';
        }

        $time = time();
        return <<<HTML
<label>Current Image:
    <img class="image-user" src="/{$this->data}?{$time}" />
</label>
HTML;
    }
}
