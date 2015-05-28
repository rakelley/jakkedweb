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
 * View class for animal shelter gallery
 */
class Shelters extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData,
    \rakelley\jhframe\interfaces\view\IHasMetaData
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\GetsMetaData,
        \main\GalleryViewTrait;

    /**
     * Animal repo instance
     * @var object
     */
    protected $animals;


    /**
     * @param \main\repositories\Animals $animals
     */
    function __construct(
        \main\repositories\Animals $animals
    ) {
        $this->animals = $animals;

        $this->galleryClass = 'gallery-large';
        $this->galleryName = 'shelters';
        $this->metaRoute = 'gallery/shelters';
    }


    /**
     * {@inheritdoc}
     * @see \main\traits\view\GalleryView::getPhotoData
     */
    protected function getPhotoData()
    {
        return $this->animals->getAnimals();
    }


    /**
     * {@inheritdoc}
     * @see \main\traits\view\GalleryView::getHeading
     */
    protected function getHeading()
    {
        $description = <<<TEXT
We're not just a gym but an active member of our local community.  Supporting
local shelters is something very close to our hearts.  Click any image for
more details about that animal.
TEXT;
        return [
            'title' => 'Local Animals Available',
            'description' => $description,
        ];
    }


    /**
     * {@inheritdoc}
     * @see \main\traits\view\GalleryView::wrapItem
     */
    protected function wrapItem(array $item)
    {
                return <<<HTML
<a href="http://www.petfinder.com/petdetail/{$item['number']}" target="_blank">
    <img src="/{$item['photo']}" alt="Local Animals Needing Homes"
    class="gallery-item" />
</a>
<h2 class="heading-section">
    <a href="http://www.petfinder.com/petdetail/{$item['number']}"
        target="_blank">
        {$item['name']}
    </a>
</h2>
HTML;
    }
}
