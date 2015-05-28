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
 * Repository handling user authentication and implementing IAuthService
 */
class UserAuth extends \rakelley\jhframe\classes\Repository implements
    \rakelley\jhframe\interfaces\services\IAuthService
{
    /**
     * Currently loaded user
     * @var array
     */
    private $currentUser = null;
    /**
     * SessionData repository instance
     * @var object
     */
    private $sessionData;
    /**
     * SessionAbstractor service instance
     * @var object
     */
    private $sessionAbstractor;


    /**
     * @param SessionData                                              $sessionData
     * @param \rakelley\jhframe\interfaces\services\ISessionAbstractor $sessionAbstractor
     */
    function __construct(
        SessionData $sessionData,
        \rakelley\jhframe\interfaces\services\ISessionAbstractor $sessionAbstractor
    ) {
        $this->sessionData = $sessionData;
        $this->sessionAbstractor = $sessionAbstractor;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\services\IAuthService::getUser()
     */
    public function getUser($key=null)
    {
        if (!$this->currentUser) {
            $this->currentUser =
                $this->sessionData->getSession($this->sessionAbstractor->getId());
        }

        if (!$key) {
            return $this->currentUser;
        } else {
            return (isset($this->currentUser[$key])) ?
                   $this->currentUser[$key] : null;
        }
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\services\IAuthService::logIn()
     */
    public function logIn($username)
    {
        $this->sessionAbstractor->newSession();

        $this->sessionData->createSession($this->sessionAbstractor->getId(),
                                          $username);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\services\IAuthService::logOut()
     */
    public function logOut()
    {
        $this->sessionData->destroySession($this->sessionAbstractor->getId());
        $this->sessionAbstractor->closeSession();
        $this->currentUser = null;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\services\IAuthService::checkPermission()
     */
    public function checkPermission($permission)
    {
        $this->getUser();

        if (!$this->currentUser) {
            return false;
        } elseif ($permission === $this::PERMISSION_ALLUSERS) {
            return true;
        } else {
            return in_array($permission, $this->currentUser['permissions']);
        }
    }
}
