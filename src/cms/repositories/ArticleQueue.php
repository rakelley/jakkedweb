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
 * Repository for queued articles awaiting approval
 */
class ArticleQueue extends \main\repositories\Article implements
    \main\IQueueRepository
{

    function __construct(
        \cms\models\ArticleQueue $articles,
        \rakelley\jhframe\interfaces\services\IFilter $filter,
        \main\repositories\UserAccount $user
    ) {
        parent::__construct($user, $filter, $articles);
    }
}
