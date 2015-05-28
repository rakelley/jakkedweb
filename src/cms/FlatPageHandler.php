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
 * FileHandler for static main view files
 */
class FlatPageHandler extends \rakelley\jhframe\classes\FileHandler
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FileHandler::$relativePath
     */
    protected $relativePath = '../src/main/routes/Flat/views/';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FileHandler::$validTypes
     */
    protected $validTypes = ['text/html'];


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\IFileHandler::Validate()
     */
    public function Validate($file)
    {
        throw new \BadMethodCallException(
            'FlatPageHandler does not implement file validation',
            500
        );
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\IFileHandler::Delete()
     */
    public function Delete($name)
    {
        $this->fileSystem->Delete($this->getPath($name));
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\IFileHandler::Read()
     */
    public function Read($name)
    {
        $path = $this->getPath($name);
        if ($this->fileSystem->Exists($path)) {
            return $this->fileSystem->getFileWithPath($path)
                                    ->getContent();
        } else {
            return null;
        }
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\IFileHandler::Write()
     */
    public function Write($name, $content)
    {
        $this->fileSystem->getFileWithPath($this->getPath($name))
                         ->setContent($content);
    }


    private function getPath($name)
    {
        return $this->directory . strtolower($name) . '.html';
    }
}
