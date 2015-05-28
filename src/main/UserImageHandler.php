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
 * FileHandler for user profile photos
 */
class UserImageHandler extends \rakelley\jhframe\classes\FileHandler
{
    use \rakelley\jhframe\traits\GetsMimeType,
        \rakelley\jhframe\traits\UploadHandler;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FileHandler::$maxFileSize
     */
    protected $maxFileSize = 100000;
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FileHandler::$relativePath
     */
    protected $relativePath = 'images/userimages/';
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FileHandler::$validTypes
     */
    protected $validTypes = ['image/gif', 'image/png', 'image/jpeg'];
}
