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
 * View for powerlifting record query results
 */
class PlQuery extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * Records repo instance
     * @var object
     */
    private $records;


    /**
     * @param \main\repositories\PlRecords $records
     */
    function __construct(
        \main\repositories\PlRecords $records
    ) {
        $this->records = $records;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->records->getQuery($this->parameters);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $parameterList = $this->fillParameterList($this->parameters);

        if (!$this->data) {
            $headings = <<<HTML
<h2 class="alignment-center">No Records Found For This Query</h2>
HTML;
            $rows = '';
        } else {
            $columns = $this->setColumns(array_keys($this->parameters));

            $headings = implode('', array_map(
                function($column) {
                    return '<th>' . ucfirst($column) . '</th>';
                },
                $columns
            ));

            $rows = implode('', array_map(
                function($record) use ($columns) {
                    return $this->fillRow($record, $columns);
                },
                $this->data
            ));
        }

        $this->viewContent = <<<HTML
<p class="alignment-center">
    {$parameterList}
</p>

<table>
    <thead>
        <tr>
            {$headings}
        </tr>
    </thead>
    <tbody>
        {$rows}
    </tbody>
</table>

HTML;
    }


    /**
     * Implodes parameter list into key value string
     * 
     * @param  array  $parameters Query parameters
     * @return string
     */
    private function fillParameterList(array $parameters)
    {
        return implode(', ', array_map(
            function ($key, $value) {
                return ucfirst($key) . ' - ' . $value;
            },
            array_keys($parameters),
            array_values($parameters)
        ));
    }


    /**
     * Compares current query parameters to master set of columns to determine
     * which need to be shown
     * 
     * @param  array $parameters Query parameters
     * @return array             List of columns with no parameter set
     */
    private function setColumns(array $parameters)
    {
        $default = ['name', 'record', 'meet', 'date'];
        $optional = ['division', 'gear', 'class', 'lift'];

        return array_merge(array_diff($optional, $parameters), $default);
    }


    /**
     * Generates markup for a single record
     * 
     * @param  array  $record  Record properties
     * @param  array  $columns List of columns to turn into cells
     * @return string
     */
    private function fillRow(array $record, array $columns)
    {
        $row = implode('', array_map(
            function($column) use ($record) {
                $th = ucfirst($column);
                return '<td data-th="' . $th . '">' . $record[$column] . '</td>';
            },
            $columns
        ));

        return '<tr>' . $row . '</tr>';
    }
}
