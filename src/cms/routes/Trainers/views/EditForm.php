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
 * FormView for editing an existing trainer
 */
class EditForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'name' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'name',
            ],
            'data-binding' => 'name',
            'required' => true,
            'sanitize' => ['filters' => ['word' => '\s']],
        ],
        'visible' => [
            'label' => 'Visibility: ',
            'type' => 'select',
            'attr' => [
                'name' => 'visible',
            ],
            'options' => ['Not Visible', 'Visible'],
            'selected-data' => 'visible',
            'required' => true,
            'sanitize' => ['filters' => ['word' => '\s']],
        ],
        'profile' => [
            'label' => 'Trainer Profile: <small>HTML is allowed but do not use
                        style tags or inline css.</small>',
            'type' => 'textarea',
            'attr' => [
                'name' => 'profile',
                'class' => 'valTextarea',
                'data-tinymce' => '',
            ],
            'data-binding' => 'profile',
            'required' => true,
            'sanitize' => ['filters' => 'tidytext'],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Update Trainer',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'trainers/edit',
        'method'          => 'post',
        'data-valmethods' => 'redirect-show',
        'data-redirect'   => 'trainers/',
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
        $this->data = $this->trainers->getTrainer($this->parameters['name']);
        if (!$this->data) {
            throw new \DomainException(
                'Trainer ' . $this->parameters['name'] . ' Not Found',
                404
            );
        }

        //Convert boolean to human string
        $this->data['visible'] = ($this->data['visible']) ? 'Visible' :
                                 'Not Visible';
    }
}
