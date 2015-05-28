<?php
/**
 * @package jakkedweb
 * @subpackage main
 * 
 * All content covered under The MIT License except where included 3rd-party
 * vendor files are licensed otherwise.
 * 
 * @license http://opensource.org/licenses/MIT The MIT License
 * @author Ryan Kelley
 * @copyright 2011-2015 Jakked Hardcore Gym
 */
namespace main\routes\Records\views;

/**
 * FormView for powerlifting record queries
 */
class PlForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'class'         => 'column-twelve column-offset-two',
        'action'        => 'records/powerlifting',
        'method'        => 'get',
        'data-getform'  => '',
        'data-stateful' => '',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'indexes' => [
            'method' => 'fillIndexes',
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Get Records',
            ],
        ],
    ];
    /**
     * Records repo instance
     * @var object
     */
    private $records;
    /**
     * Name of index required for submission
     * @var string
     */
    private $requiredIndex = 'gender';


    /**
     * @param \rakelley\jhframe\interfaces\services\IFormBuilder $builder
     * @param \main\repositories\PlRecords                       $records
     */
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
        $this->data = $this->records->getIndex();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $this->viewContent = $this->standardConstructor();
        $this->viewContent .= <<<HTML
<script data-main="js/src/main/main" data-page="main/routes/Records/plform"
    src="js/require.js"></script>

HTML;
    }


    /**
     * Bound method to create indexes field
     * 
     * @return string
     */
    protected function fillIndexes()
    {
        return implode('', array_map(
            [$this, 'fillIndex'],
            array_keys($this->data),
            array_values($this->data)
        ));
    }


    /**
     * Generates markup for a single index
     * 
     * @param  string  $name   Name of select field
     * @param  array   $values Options for select field
     * @return string
     */
    private function fillIndex($name, array $values)
    {
        $options = implode('', array_map(
            function ($value) {
                return '<option value="' . $value . '">' . $value . '</option>';
            },
            $values
        ));
        $isRequired = ($name === $this->requiredIndex) ? ' required' : '';
        $ucName = strtoupper($name);

        return <<<HTML
<select name="{$name}"{$isRequired}>
    <option selected value="">- {$ucName} -</option>
    {$options}
</select>
HTML;
    }
}
