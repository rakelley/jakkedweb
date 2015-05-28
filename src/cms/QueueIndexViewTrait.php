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

namespace cms;

/**
 * Trait for views which represent the standardized index of a queue.
 * 
 * Using classes need to implement \rakelley\jhframe\interfaces\view\IRequiresData so
 * that ::fetchData is called.
 */
trait QueueIndexViewTrait
{
    /**
     * Repository instance for queue, should be set by class constructor.
     * Must implement \main\IQueueRepository.
     * @var object
     */
    protected $queueRepo;
    /**
     * Store for data fetched from repo
     * @var array
     */
    protected $data;
    /**
     * Human-readable name of queue, should be set by class constructor
     * @var string
     */
    protected $queueName;
    /**
     * Unqualified name of RouteController for queue, should be set by class
     * constructor
     * @var string
     */
    protected $queueController;


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->queueRepo->getAll();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView
     */
    public function constructView()
    {
        if (!$this->data) {
            $title = $this->queueName . ' Queue Empty';
            $queue = '';
        } else {
            $title = $this->queueName . 's';
            $queue = $this->fillTable($this->data);
        }

        $this->viewContent = <<<HTML
<div class="column-eight">
    <h4>{$title}</h4>
    {$queue}
</div>
HTML;
    }


    /**
     * Converts queue data into rows and inserts into table
     * 
     * @param  array  $queue Fetched data from queue repo
     * @return string
     */
    protected function fillTable(array $queue)
    {
        $controller = $this->queueController;
        $rows = implode('', array_map(
            function($item) use ($controller) {
                return <<<HTML
<tr>
    <td data-th="Date">{$item['date']}</td>
    <td data-th="Edit">
        <a class="button" href="/{$controller}/item?id={$item['id']}">
            Edit
        </a>
    </td>
</tr>
HTML;
            },
            $queue
        ));

        return <<<HTML
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        {$rows}
    </tbody>
</table>
HTML;
    }
}
