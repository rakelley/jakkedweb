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
 * View class for all standard gallery views
 * Gallery identity set through parameter passing from controller
 */
class Gallery extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData,
    \rakelley\jhframe\interfaces\view\IHasMetaData
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\GetsMetaData,
        \main\GalleryViewTrait;


    function __construct()
    {
        $this->galleryClass = 'gallery-lightbox';
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\ITakesParameters::setParameters()
     */
    public function setParameters(array $parameters=null)
    {
        $this->galleryName = $parameters['gallery'];
        $this->metaRoute = 'gallery/' . $parameters['gallery'];
    }


    /**
     * {@inheritdoc}
     * @see \main\traits\view\GalleryView::getHeading
     */
    protected function getHeading()
    {
        return [
            'title' => ucwords(str_replace('_', ' ', $this->galleryName)),
            'description' => 'Click an image for a larger view.',
        ];
    }


    /**
     * {@inheritdoc}
     * @see \main\traits\view\GalleryView::wrapItem
     */
    protected function wrapItem(array $item)
    {
                return <<<HTML
<a href="{$item['original']}" data-lightbox="gallery">
    <img src="{$item['thumb']}" alt="gallery image" class="gallery-item" />
</a>
HTML;
    }
}
