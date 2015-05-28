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

namespace cms\routes\Auth\actions;

/**
 * Action for user logout
 */
class Logout extends \rakelley\jhframe\classes\Action
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\Action::$touchesData
     */
    protected $touchesData = false;
    /**
     * AuthService instance
     * @var object
     */
    private $auth;


    /**
     * @param \rakelley\jhframe\interfaces\services\IAuthService $auth
     */
    function __construct(
        \rakelley\jhframe\interfaces\services\IAuthService $auth
    ) {
        $this->auth = $auth;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->auth->logOut();
    }
}
