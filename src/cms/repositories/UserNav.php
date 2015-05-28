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

namespace cms\repositories;

/**
 * Repository for permission-based CMS navigation data
 */
class UserNav extends \rakelley\jhframe\classes\Repository
{
    /**
     * CmsNav model instance
     * @var object
     */
    private $nav;


    function __construct(
        \cms\models\CmsNav $nav
    ) {
        $this->nav = $nav;
    }


    /**
     * Get sorted list of navigation entries for user
     * 
     * @param  string $username
     * @return array
     */
    public function getNav($username)
    {
        $entries = $this->nav->getNav($username);
        if (!$entries) {
            return null;
        }

        $nav = [];
        array_walk(
            $entries,
            function($entry) use (&$nav) {
                $nav[$entry['category']][] = [
                    'title' => $entry['title'],
                    'path'  => $entry['path'],
                ];
            }
        );
        ksort($nav);

        return $nav;
    }
}
