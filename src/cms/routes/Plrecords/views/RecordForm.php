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

namespace cms\routes\Plrecords\views;

/**
 * FormView for updating records
 */
class RecordForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'name' => [
            'type' => 'text',
            'label' => 'Lifter:',
            'attr' => [
                'name' => 'name',
            ],
            'required' => true,
            'sanitize' => 'default',
        ],
        'record' => [
            'type' => 'text',
            'label' => 'Record:',
            'attr' => [
                'name' => 'record',
            ],
            'required' => true,
            'sanitize' => ['filters' => ['float', 'strval']],
        ],
        'gender' => [
            'type' => 'select',
            'label' => 'Gender:',
            'attr' => [
                'name' => 'gender',
            ],
            'options' => [
                'empty' => '---',
            ],
            'data-binding' => 'gender',
            'required' => true,
            'sanitize' => ['filters' => 'word'],
        ],
        'division' => [
            'type' => 'select',
            'label' => 'Division:',
            'attr' => [
                'name' => 'division',
            ],
            'options' => [
                'empty' => '---',
            ],
            'data-binding' => 'division',
            'required' => true,
            'sanitize' => ['filters' => ['word' => '\s+-']],
        ],
        'gear' => [
            'type' => 'select',
            'label' => 'Gear:',
            'attr' => [
                'name' => 'gear',
            ],
            'options' => [
                'empty' => '---',
            ],
            'data-binding' => 'gear',
            'required' => true,
            'sanitize' => ['filters' => 'word'],
        ],
        'class' => [
            'type' => 'select',
            'label' => 'Class:',
            'attr' => [
                'name' => 'class',
            ],
            'options' => [
                'empty' => '---',
            ],
            'data-binding' => 'class',
            'required' => true,
            'sanitize' => ['filters' => ['word' => '.']],
        ],
        'lift' => [
            'type' => 'select',
            'label' => 'Lift:',
            'attr' => [
                'name' => 'lift',
            ],
            'options' => [
                'empty' => '---',
            ],
            'data-binding' => 'lift',
            'required' => true,
            'sanitize' => ['filters' => 'word'],
        ],
        'meet' => [
            'type' => 'select',
            'label' => 'Meet:',
            'attr' => [
                'name' => 'meet',
            ],
            'options' => [
                'empty' => '---',
            ],
            'data-binding' => 'meet',
            'required' => true,
            'sanitize' => ['filters' => ['word' => '\s']],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Update Record',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'plrecords/update',
        'method'          => 'post',
        'data-valmethods' => 'reload-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Update A Single Record';
    /**
     * PlRecords repo instance
     * @var object
     */
    private $records;


    function __construct(
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder,
        \main\repositories\PlRecords $records
    ) {
        $this->records = $records;

        parent::__construct($builder);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->records->getFields();
    }
}
