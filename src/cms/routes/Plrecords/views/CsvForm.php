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
 * FormView for updating records via CSV file upload
 */
class CsvForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'MAX_FILE_SIZE' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'MAX_FILE_SIZE',
                'value' => '5000000',
            ],
        ],
        'file' => [
            'type' => 'file',
            'attr' => [
                'name' => 'file',
            ],
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Upload File',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'plrecords/csvupdate',
        'method'          => 'post',
        'enctype'         => 'multipart/form-data',
        'data-valmethods' => 'reload-show',
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$title
     */
    protected $title = 'Upload a CSV Records File';
    /**
     * Records repo instance
     * @var object
     */
    private $records;


    /**
     * @param \main\repositories\PlRecords $records
     * @param \rakelley\jhframe\interfaces\services\IFormBuilder $builder
     */
    function __construct(
        \main\repositories\PlRecords $records,
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder
   ) {
        $this->records = $records;
        parent::__construct($builder);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequriesData::fetchData()
     */
    public function fetchData()
    {
        $this->data['fields'] = $this->records->getFields();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $ruleBlock = $this->fillRuleBlock($this->data['fields']);
        $form = $this->standardConstructor();

        $this->viewContent = $ruleBlock . $form;
    }


    /**
     * Generates text block explaining rules for csv uploads
     * 
     * @param  array  $fields Assoc array of fixed-value columns and their
     *                        possible values
     * @return string
     */
    private function fillRuleBlock(array $fields)
    {
        $fieldList = implode('</li><li>', array_map(
            function($values, $field) {
                return ucfirst($field) . ': ' . implode(', ', $values);
            },
            $fields,
            array_keys($fields)
        ));

        return <<<HTML
<section class="column-sixteen">
    <p>You may update many records at once using a .csv (comma seperated
        variable) file, which can be made in Excel or Google Spreadsheets or the
        like. In order for the file to be accepted, it must follow these rules:
    </p>
    <ul>
        <li>The meet the records belong to must have already been added</li>
        <li>The columns must be in the following order: Gender, Division, Gear,
            Class, Lift, Meet, Name, Record
        </li>
        <li>All cells must have valid values</li>
        <li>There must be no heading row, only record rows</li>
        <li>The file must be comma delimited (this is usually the default)</li>
        <li>The file must be less than 5mB</li>
    </ul>
    <p>Records which are lower than an existing record for the same category
        will be ignored, so you can safely add an entire meet at once without
        having to manually filter new records.</p>
    <p>The following values are valid for the fixed-value columns:</p>
    <ul>
        <li>
            {$fieldList}
        </li>
    </ul>
    <p>Additionally, Names must only contain letters and spaces and Records must
        be numbers with at most two decimal places.
    </p>
</section>

HTML;
    }
}
