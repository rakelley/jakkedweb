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
 * Repository for handling user sessions
 */
class SessionData extends \rakelley\jhframe\classes\Repository
{
    use \rakelley\jhframe\traits\GetsServerProperty;

    /**
     * Cache service instance
     * @var object
     */
    private $cache;
    /**
     * SessionData model instance
     * @var object
     */
    private $dataModel;
    /**
     * Session lifetime in seconds
     * @var int
     */
    private $expiration = 86400;
    /**
     * UserAccount repo instance
     * @var object
     */
    private $userAccount;


    function __construct(
        \cms\models\SessionData $dataModel,
        \rakelley\jhframe\interfaces\services\IKeyValCache $cache,
        \main\repositories\UserAccount $userAccount
    ) {
        $this->cache = $cache;
        $this->dataModel = $dataModel;
        $this->userAccount = $userAccount;
    }


    /**
     * Get a valid session matching id if it exists, uses cacheing if possible.
     * 
     * @param  string     $id Session id
     * @return array|null     Session properties or null if not found/invalid
     */
    public function getSession($id)
    {
        $cacheKey = $this->getCacheKey($id);
        $cached = $this->cache->Read($cacheKey);

        if ($cached) {
            $data = $cached;
        } else {
            $data = $this->dataModel->Get($id);
            if (!$data) {
                return null;
            }

            $data['fullname'] =
                $this->userAccount->getFullname($data['username']);
            $data['permissions'] =
                $this->userAccount->getPermissions($data['username']);
        }

        if (!$this->Validate($data)) {
            $this->destroySession($id);
            $data = null;
        } elseif (!$cached) {
            $this->cache->Write($data, $cacheKey);
        }

        return $data;
    }


    /**
     * Create a new session
     * 
     * @param  string $id       Session ID
     * @param  string $username User owning session
     * @return void
     */
    public function createSession($id, $username)
    {
        $this->destroySession([$id, $username]);

        $input = [
            'id' => $id,
            'username' => $username,
            'expiry' => date('Y-m-d H:i:s', time() + $this->expiration),
            'ip' => $this->getServerProp('REMOTE_ADDR'),
        ];
        $this->dataModel->Add($input);

        $this->userAccount->setLastLogin($username, date('Y-m-d H:i:s'));
    }


    /**
     * Remove all sessions matching keys from model and cache
     * 
     * @param  array|string $keys String session id or username or array of same
     * @return void
     */
    public function destroySession($keys)
    {
        $keys = (array) $keys;
        $this->dataModel->Remove($keys);

        $cacheKeys = array_map([$this, 'getCacheKey'], $keys);
        $this->cache->Purge($cacheKeys);
    }


    /**
     * Validates session
     * 
     * @param  array   $session Session properties
     * @return boolean
     */
    private function Validate(array $session)
    {
        if (time() > strtotime($session['expiry']) ||
            ($this->getServerProp('REMOTE_ADDR') !== $session['ip'])
        ) {
            return false;
        }
        return true;
    }


    /**
     * Standardized internal method for getting key for cache store
     * 
     * @param  string $id Session key
     * @return string
     */
    private function getCacheKey($id)
    {
        return 'SessionData_' . $id;
    }
}
