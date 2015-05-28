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
 * Composite view with overview of all trainers
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IHasSubViews,
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\InterpolatesPlaceholders,
        \rakelley\jhframe\traits\view\MakesSubViews;

    /**
     * Trainers repo instance
     * @var object
     */
    protected $trainers;
    /**
     * Store for fetched data
     * @var array
     */
    protected $data;


    /**
     * @param \main\repositories\Trainers $trainers
     */
    function __construct(
        \main\repositories\Trainers $trainers
    ) {
        $this->trainers = $trainers;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->trainers->getAll();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        if ($this->data) {
            $trainers = implode('', array_map([$this, 'fillRow'], $this->data));
        } else {
            $trainers =
                '<tr><td colspan="4">There Are Currently No Trainers</td></tr>';
        }

        $this->viewContent = <<<HTML
<h2 class="page-heading">Trainers</h2>

<section class="column-four alignment-center">
    <a class="button" href="/trainers/new">
        Add A New Trainer
    </a>
</section>

<section class="column-twelve">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Visible</th>
                <th>Edit Trainer</th>
                <th>Delete Trainer</th>
            </tr>
        </thead>
        <tbody>
            {$trainers}
        </tbody>
    </table>
</section>

HTML;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\traits\view\MakesSubViews::getSubViewList()
     */
    protected function getSubViewList()
    {
        return [
            'deleteForm' => 'DeleteForm',
        ];
    }


    /**
     * Generates markup for a single trainer's table row
     * 
     * @param  array  $trainer Trainer properties
     * @return string
     */
    private function fillRow(array $trainer)
    {
        $visibility = ($trainer['visible']) ? 'Yes' : 'No';
        $delete = $this->interpolatePlaceholders($this->subViews['deleteForm'],
                                                 ['name' => $trainer['name']]);
        return <<<HTML
<tr>
    <td data-th="Name">{$trainer['name']}</td>
    <td data-th="Visible">{$visibility}</td>
    <td data-th="Edit">
        <a class="button" href="/trainers/editing?name={$trainer['name']}">
            Edit
        </a>
    </td>
    <td data-th="Delete">{$delete}</td>
</tr>

HTML;
    }
}
