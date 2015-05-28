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
namespace main;

/**
 * Trait for gallery views.
 * 
 * Views using this must implement \rakelley\jhframe\interfaces\view\IRequiresData
 * so that ::fetchData gets called.
 */
trait GalleryViewTrait
{
    /**
     * Internal store for fetched data
     * @var array
     */
    protected $data = [];
    /**
     * CSS class for gallery div.
     * Should be set prior to ::constructView call
     * @var string
     */
    protected $galleryClass;
    /**
     * Name of gallery, used to determine position in nav and title.
     * Should be set prior to ::constructView call
     * @var string
     */
    protected $galleryName;
    protected $galleryRepoClass = '\main\repositories\Gallery';


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data['nav'] = $this->getNavData();
        $this->data['photos'] = $this->getPhotoData();
    }


    /**
     * {@inheritdoc}
     * @see rakelley\jhframe\interfaces\view\IView::constructView
     */
    public function constructView()
    {
        $nav = $this->fillNav($this->data['nav']);
        $heading = $this->getHeading();
        $gallery = $this->fillGallery($this->data['photos']);

        $this->viewContent = <<<HTML
<div class="{$this->galleryClass}">
    <section class="gallery-header">
        {$nav}
        <h1 class="gallery-title">{$heading['title']}</h1>
        <p>{$heading['description']}</p>
    </section>

    <ul>
        {$gallery}
    </ul>
</div>

<script data-main="js/src/main/main" data-page="lib/gallery"
    src="js/require.js"></script>
HTML;
    }


    /**
     * ServiceLocator getter, can be implemented through
     * \rakelley\jhframe\traits\ServiceLocatorAware
     */
    abstract protected function getLocator();


    /**
     * Provide gallery details for heading
     * 
     * @return array
     *     string 'title'       Gallery title
     *     string 'description' Gallery description
     */
    abstract protected function getHeading();

 
    /**
     * Wrap a gallery item with appropriate markup and return it
     * 
     * @param  array  $item Properties of item to wrap
     * @return string
     */
    abstract protected function wrapItem(array $item);


    /**
     * Gets gallery navigation data from galleryRepo
     * 
     * @return array
     */
    protected function getNavData()
    {
        $data = $this->getLocator()->Make($this->galleryRepoClass)
                                   ->getGalleryNav($this->galleryName);
        if (!$data) {
            throw new \DomainException(
                'Gallery ' . $this->galleryName . ' Not Found',
                404
            );
        }

        return $data;
    }


    /**
     * Gets gallery photo data
     * 
     * @return array
     */
    protected function getPhotoData()
    {
        return $this->getLocator()->Make($this->galleryRepoClass)
                                  ->getGalleryPhotos($this->galleryName);
    }


    /**
     * Convert list of photos into gallery content by calling wrap on each and
     * then concenating all
     * 
     * @param  array  $photos Photos to convert
     * @return string         Primary content of gallery
     */
    protected function fillGallery($photos)
    {
        if (!$photos) return '<h2>No Photos Found</h2>';

        return implode('', array_map(
            function($photo) {
                $item = $this->wrapItem($photo);
                return '<li class="gallery-item-wrapper">' . $item . '</li>';
            },
            $photos
        ));
    }


    /**
     * Converts navigation data to markup
     * 
     * @param  array  $nav
     *     string|null 'previous' Name of previous gallery, if any
     *     string|null 'next'     Name of next gallery, if any
     * @return string
     */
    protected function fillNav(array $nav)
    {
        $content = '';
        if ($nav['previous']) {
            $content .= <<<HTML
<a href="gallery/{$nav['previous']}" class="gallery-nav-previous" rel="prev">
    Previous
</a>
HTML;
        }
        if ($nav['next']) {
            $content .= <<<HTML
<a href="gallery/{$nav['next']}" class="gallery-nav-next" rel="next">
    Next
</a>
HTML;
        }

        return $content;
    }
}
