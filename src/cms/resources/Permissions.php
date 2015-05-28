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

namespace cms\resources;

use \rakelley\jhframe\interfaces\services\IAuthService;

/**
 * Contains all permissions from permissions database table in constant form
 *
 * RouteControllers requiring authentication should use these constants rather
 * than an inline value
 */
class Permissions
{
    const ALLUSERS = IAuthService::PERMISSION_ALLUSERS;
    const ARTICLE_AUTHOR = 'writearticle';
    const ARTICLE_EDITOR = 'editarticle';
    const FILES = 'uploadfiles';
    const GALLERY = 'editgallery';
    const NAV = 'editnav';
    const QUEUES = 'managequeues';
    const RECORDS = 'editrecords';
    const PAGES = 'editpages';
    const USERS = 'editusers';
    const WIDGETS = 'editwidgets';

}
