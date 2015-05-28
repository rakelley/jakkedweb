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
namespace main\repositories;

/**
 * Repository containing data and image access for photo gallery pages
 */
class Gallery extends \rakelley\jhframe\classes\Repository
{
    /**
     * GalleryImageHandler instance, set by constructor
     * @var object
     */
    protected $imageHandler;
    /**
     * Navigation repo instance, set by constructor
     * @var object
     */
    protected $navEntries;
    /**
     * Page metadata repo instance, set by constructor
     * @var object
     */
    protected $pageData;


    function __construct(
        PageData $pageData,
        Navigation $navEntries,
        \main\GalleryImageHandler $imageHandler
    ) {
        $this->pageData = $pageData;
        $this->navEntries = $navEntries;
        $this->imageHandler = $imageHandler;
    }


    /**
     * Returns if a gallery exists
     * 
     * @param  string  $gallery Gallery name
     * @return boolean
     */
    public function galleryExists($gallery)
    {
        return !!$this->getGallery($gallery);
    }


    /**
     * Returns a list of all gallery names
     * 
     * @return array
     */
    public function getAll()
    {
        $list = $this->pageData->getGroup('gallery');
        if (!$list) {
            return null;
        }

        $galleries = array_map(
            function($gallery) {
                return substr($gallery, strpos($gallery, '/')+1);
            },
            $list           
        );

        //Filter needed to remove null gallery index
        return array_values(array_filter($galleries));
    }


    /**
     * Gets details for a gallery
     * 
     * @param  string  $gallery Gallery name
     * @return array|null
     */
    public function getGallery($gallery)
    {
        $route = 'gallery/' . $gallery;
        return $this->pageData->getPage($route);
    }

    /**
     * Add a new gallery
     * 
     * @param  array $input
     * @return void
     */
    public function addGallery(array $input)
    {
        // Create image directories
        $this->imageHandler->addGallery($input['name']);

        // Add image to index
        $imageKey = 'galleryindex/' . $input['name'];
        $this->imageHandler->Write($imageKey, $input['indeximage']);
        unset($input['indeximage']);

        // Add to page table
        $input['route'] = 'gallery/' . $input['name'];
        $input['pagegroup'] = 'gallery';
        $input['priority'] = '0.3';
        $this->pageData->addPage($input);

        // Add to site navigation
        $title = ucwords(str_replace('_', ' ', $input['name']));
        $nav = [
            'route' => 'gallery/' . $input['name'],
            'title' => $title,
            'parent' => 'gallery',
            'description' => $title . ' Photos',
        ];
        $this->navEntries->addEntry($nav);
    }

    /**
     * Delete a gallery
     * 
     * @param  string  $gallery Gallery name
     * @return void
     */
    public function deleteGallery($gallery)
    {
        $route = 'gallery/' . $gallery;

        // Remove from site navigation
        $this->navEntries->deleteEntry($route);

        // Remove from page table
        $this->pageData->deletePage($route);

        // Remove image from index
        $imageKey = 'galleryindex/' . $gallery;
        $this->imageHandler->Delete($imageKey);

        // Delete image directories
        $this->imageHandler->deleteGallery($gallery);
    }


    /**
     * Get inter-gallery navigation position
     * 
     * @param  string     $gallery Gallery name
     * @return array|null          Null if gallery not found
     *     string|null 'previous' Previous gallery, if any
     *     string|null 'next'     Next gallery, if any
     */
    public function getGalleryNav($gallery)
    {
        $galleries = $this->getAll();
        $index = array_search($gallery, $galleries);
        if ($index === false) {
            return null;
        }

        $previous = ($index !== 0) ? $galleries[$index-1] : null;
        $next = ($index !== count($galleries)-1) ? $galleries[$index+1] : null;

        return ['previous' => $previous, 'next' => $next];
    }


    /**
     * Get list of all photos for a gallery
     * 
     * @param  string $gallery Gallery name
     * @return array
     */
    public function getGalleryPhotos($gallery)
    {
        return $this->imageHandler->getGallery($gallery);
    }


    /**
     * Get detailed index of all galleries
     * 
     * @return array|null  Two-level array, each sub-array contains
     *     string 'name'  Name of gallery
     *     string 'title' Pretty name of gallery
     *     string 'image' Relative path to index image of gallery
     */
    public function getIndex()
    {
        $galleries = $this->getAll();
        if (!$galleries) {
            return null;
        }

        return array_map(
            function($gallery) {
                $image = $this->imageHandler->makeRelative(
                    $this->imageHandler->Read('galleryindex/' . $gallery)
                );
                return [
                    'name' => $gallery,
                    'title' => ucwords(str_replace('_', ' ', $gallery)),
                    'image' => $image,
                ];
            },
            $galleries
        );
    }


    /**
     * Pass image to imagehandler for validation
     *
     * @see \rakelley\jhframe\interfaces\IFileHandler::Validate()
     * @param  mixed   $image
     * @return boolean
     */
    public function validateImage($image)
    {
        return $this->imageHandler->Validate($image);
    }
}
