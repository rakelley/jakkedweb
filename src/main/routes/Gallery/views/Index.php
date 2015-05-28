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
namespace main\routes\Gallery\views;

/**
 * Index view of all image galleries
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData,
    \rakelley\jhframe\interfaces\view\IHasMetaData
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\GetsMetaData;

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

        $this->metaRoute = 'gallery/';
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->galleries->getIndex();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $galleries = implode('', array_map([$this, 'fillGallery'], $this->data));

        $this->viewContent = <<<HTML
<div class="gallery-large">
    <section class="gallery-header">
        <h1 class="gallery-title">Jakked Hardcore Photo Galleries</h1>
        <p>Click an image to go to a specific gallery.</p>
    </section>

    <ul>
        {$galleries}
    </ul>
</div>
HTML;
    }


    /**
     * Generates markup for a single gallery
     * 
     * @param  array  $gallery Gallery properties
     * @return string
     */
    private function fillGallery(array $gallery)
    {
            return <<<HTML
<li class="gallery-item-wrapper">
    <a href="gallery/{$gallery['name']}">
        <img src="{$gallery['image']}" alt="image" class="gallery-item" />
    </a>
    <br />
    <a href="gallery/{$gallery['name']}">
        <h2 class="heading-section">{$gallery['title']}</h2>
    </a>
</li>

HTML;
    }
}
