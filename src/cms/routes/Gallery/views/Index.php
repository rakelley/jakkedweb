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

namespace cms\routes\Gallery\views;

/**
 * Composite View for viewing/adding/deleting galleries
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
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * Gallery repo instance
     * @var object
     */
    private $galleries;


    /**
     * @param \main\repositories\Gallery $galleries
     */
    function __construct(
        \main\repositories\Gallery $galleries
    ) {
        $this->galleries = $galleries;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->galleries->getAll();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $galleries = implode('', array_map(
            [$this, 'fillGallery'],
            $this->data
        ));

        $this->viewContent = <<<HTML
<h2 class="page-heading">Add or Delete Image Galleries</h2>

<section class="column-five margin-bottom">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            {$galleries}
        </tbody>
    </table>
</section>

<section class="column-eleven">
    {$this->subViews['addForm']}
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
            'addForm' => 'AddForm',
            'deleteForm' => 'DeleteForm',
        ];
    }


    /**
     * Generates markup for a single gallery row
     * 
     * @param  string $name Gallery name
     * @return string
     */
    private function fillGallery($name)
    {
        $delete = $this->interpolatePlaceholders($this->subViews['deleteForm'],
                                                 ['gallery' => $name]);

        return <<<HTML
<tr>
    <td data-th="Name">{$name}</td>
    <td data-th="Delete">{$delete}</td>
</tr>

HTML;
    }
}
