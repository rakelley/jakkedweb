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

use \rakelley\jhframe\classes\InputException;

/**
 * Repository for user account recovery
 */
class Recovery extends \rakelley\jhframe\classes\Repository
{
    /**
     * Crypto service
     * @var object
     */
    private $crypto;
    /**
     * AccountRecovery model instance
     * @var object
     */
    private $tokens;
    /**
     * Token lifetime in seconds, 86400 is 24 hours
     * @var integer
     */
    private $expiration = 86400;


    function __construct(
        \cms\models\AccountRecovery $tokens,
        \rakelley\jhframe\interfaces\services\ICrypto $crypto
    ) {
        $this->tokens = $tokens;
        $this->crypto = $crypto;
    }


    /**
     * Gets stored entry for a user
     * 
     * @param  string     $username Entry key
     * @return array|null
     */
    public function getEntry($username)
    {
        $this->tokens->setParameters(['username' => $username]);
        return $this->tokens->entry;
    }


    /**
     * Validates that a token exists for the user and hasn't expired
     * 
     * @param  string $username
     * @param  string $token
     * @return void
     * @throws \rakelley\jhframe\classes\InputException if token invalid
     */
    public function Validate($username, $token)
    {
        $entry = $this->getEntry($username);

        if (!$entry || !$this->crypto->compareHash($token, $entry['token'])) {
            throw new InputException('Invalid or Missing Token');
        } elseif (time() > strtotime($entry['expiration'])) {
            throw new InputException('Token Has Expired');
            $this->Delete($username);
        }
    }


    /**
     * Creates a new token for the provided user and stores a hash of it
     * 
     * @param  string $username User to create token for
     * @return string           Generated token
     */
    public function Create($username)
    {
        $token = $this->crypto->createRandomString();

        $input = [
            'username' => $username,
            'token' => $this->crypto->hashString($token),
            'expiration' => date("Y-m-d H:i:s", time() + $this->expiration),
        ];
        $this->tokens->Add($input);

        return $token;
    }


    /**
     * Deletes all existing entries matching a user
     * 
     * @param  string $username
     * @return void
     */
    public function Delete($username)
    {
        $this->tokens->Delete($username);
    }
}
