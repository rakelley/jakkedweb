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
 * FileHandler for images for the Shelter Animals gallery.
 */
class AnimalImageHandler extends \rakelley\jhframe\classes\FileHandler
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FileHandler::$maxFileSize
     */
    protected $maxFileSize = 1000000;
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FileHandler::$relativePath
     */
    protected $relativePath = 'images/gallery/animals/';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FileHandler::$validTypes
     */
    protected $validTypes = ['image/jpeg'];


    /**
     * Validate file
     * 
     * @see \rakelley\jhframe\interfaces\IFileHandler::Validate()
     * @param  object  $file \rakelley\jhframe\interfaces\IFile
     * @return boolean
     */
    public function Validate($file)
    {
        if (!in_array($file->getMedia(), $this->validTypes) ||
            $file->getSize() > $this->maxFileSize
        ) {
            return false;
        }
        return true;
    }


    /**
     * Write new local image using remote URI and validate
     * 
     * @see \rakelley\jhframe\interfaces\IFileHandler::Write()
     * @param  string $key  Key to use in file creation
     * @param  string $file URI for remote file
     * @return boolean
     */
    public function Write($key, $file)
    {
        $fetched = $this->fileSystem->getRemoteFile($file);
        if (!$fetched) {
            return false;
        }

        $this->Delete($key);

        $path = $this->directory . $key . '.jpeg';
        $fileHandler = $this->fileSystem->getFileWithPath($path);

        $fileHandler->setContent($fetched);
        if (!$this->Validate($fileHandler)) {
            $fileHandler->Delete();
            return false;
        } else {
            return true;
        }
    }
}
