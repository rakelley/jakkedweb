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
 * FileHandler for article images
 */
class ArticleImageHandler extends \rakelley\jhframe\classes\FileHandler
{
    use \rakelley\jhframe\traits\GetsMimeType,
        \rakelley\jhframe\traits\UploadHandler;

    protected $maxFileSize = 500000;
    protected $relativePath = 'images/articles/';
    protected $validTypes = ['image/gif', 'image/png', 'image/jpeg'];
}
