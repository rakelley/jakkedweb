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
 * FileHandler for standard image galleries
 */
class GalleryImageHandler extends \rakelley\jhframe\classes\FileHandler
{
    use \rakelley\jhframe\traits\CreatesImageThumbnails,
        \rakelley\jhframe\traits\GetsMimeType,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\UploadHandler;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FileHandler::$maxFileSize
     */
    protected $maxFileSize = 1000000;
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FileHandler::$relativePath
     */
    protected $relativePath = 'images/gallery/';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FileHandler::$validTypes
     */
    protected $validTypes = ['image/gif', 'image/png', 'image/jpeg'];


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\IFileHandler::Delete()
     */
    public function Delete($key)
    {
        $file = $this->Read($key);

        if ($file) {
            $this->fileSystem->Delete($file);
            if ($this->isNotIndexImage($key)) {
                $thumbPath = $this->getThumbPath($file);
                $this->fileSystem->Delete($thumbPath);
            }
        }
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\IFileHandler::Write()
     */
    public function Write($key, $file)
    {
        $this->Delete($key);

        $path = $this->directory . $key . $this->getExtension($file);
        $this->fileSystem->writeUploaded($file['tmp_name'], $path);

        if ($this->isNotIndexImage($key)) {
            $this->createThumbnail($path);
        }
    }


    /**
     * Get all images for a gallery, with thumbnails
     * 
     * @param  string     $gallery Gallery name
     * @return array|null
     */
    public function getGallery($gallery)
    {
        $pattern = $this->directory . $gallery . '/*.*';
        $files = $this->fileSystem->Glob($pattern);
        if (!$files) {
            return null;
        }

        rsort($files); // Newest images first

        return array_map(
            function($file) {
                $thumb = $this->getThumbPath($file);
                return [
                    'original' => $this->makeRelative($file),
                    'thumb' => $this->makeRelative($thumb),
                ];
            },
            $files
        );
    }

    /**
     * Create directories for new gallery
     * 
     * @param  string $gallery Gallery name
     * @return void
     */
    public function addGallery($gallery)
    {
        $dir = $this->directory . $gallery;
        $thumbDir = $dir . '/thumbs';
        $this->fileSystem->createDirectory($dir);
        $this->fileSystem->createDirectory($thumbDir);     
    }

    /**
     * Delete directories and all contents for gallery
     * 
     * @param  string $gallery Gallery name
     * @return void
     */
    public function deleteGallery($gallery)
    {
        $dir = $this->directory . $gallery;
        $this->fileSystem->Delete($dir, true);
    }


    /**
     * Either full path or just galleryname/filename.ext works
     * 
     * @see \rakelley\jhframe\traits\CreatesImageThumbnails::getThumbPath()
     */
    protected function getThumbPath($original)
    {
        //ensure key isn't a full path
        $original = str_replace($this->directory, '', $original);
        $parts = explode('/', $original);

        return $this->directory . $parts[0] . '/thumbs/' . $parts[1];
    }


    /**
     * Resuable internal method for verifying an image is not a galleryindex
     * image
     * 
     * @param  string  $key Image path
     * @return boolean
     */
    protected function isNotIndexImage($key)
    {
        return (strpos($key, 'galleryindex') === false);
    }
}
