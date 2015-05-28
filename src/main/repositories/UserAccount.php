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
namespace main\repositories;

/**
 * Repository for interacting with user accounts.
 */
class UserAccount extends \rakelley\jhframe\classes\Repository
{
    /**
     * UserImageHandler instance
     * @var object
     */
    protected $photoHandler;
    /**
     * UserRoles model instance
     * @var object
     */
    protected $rolesModel;
    /**
     * Users model instance
     * @var object
     */
    protected $userModel;
    /**
     * Crypto service instance
     * @var object
     */
    protected $crypto;


    function __construct(
        \main\UserImageHandler $photoHandler,
        \main\models\Users $userModel,
        \main\models\UserRoles $rolesModel,
        \rakelley\jhframe\interfaces\services\ICrypto $crypto
    ) {
        $this->photoHandler = $photoHandler;
        $this->userModel = $userModel;
        $this->rolesModel = $rolesModel;
        $this->crypto = $crypto;
    }


    /**
     * Get all users with user admin permission
     * 
     * @return array|null
     */
    public function getAdmins()
    {
        return $this->rolesModel->getUsersByPermission('editusers');
    }


    /**
     * Get all users
     * 
     * @return array|null
     */
    public function getAll()
    {
        return $this->userModel->allusers;
    }


    /**
     * Get all users with author permission
     * 
     * @return array|null
     */
    public function getAuthors()
    {
        return $this->rolesModel->getUsersByPermission('writearticle');
    }


    /**
     * Verifies user exists
     * 
     * @param  string  $username User key
     * @return boolean
     */
    public function userExists($username)
    {
        return !!$this->userGet($username, 'user');
    }


    /**
     * Adds a single user
     * 
     * @param  array $input
     * @return void
     */
    public function addUser(array $input)
    {
        $input['password'] = $this->crypto->hashString($input['password']);

        $this->userModel->Add($input);
    }

    /**
     * Deletes a single user
     * 
     * @param  string $username User key
     * @return void
     */
    public function deleteUser($username)
    {
        $this->setPhoto($username, null);

        $this->userModel->setParameters(['username' => $username]);
        $this->userModel->Delete();
    }


    /**
     * Get fullname for a single user
     * 
     * @param  string      $username User key
     * @return string|null
     */
    public function getFullname($username)
    {
        return $this->userGet($username, 'fullname');
    }

    /**
     * Update fullname for a single user
     * 
     * @param  string $username User key
     * @param  string $value    New value
     * @return void
     */
    public function setFullname($username, $value)
    {
        $this->userSet($username, $value, 'fullname');
    }


    /**
     * Get lastlogin for a single user
     * 
     * @param  string      $username User key
     * @return string|null
     */
    public function getLastLogin($username)
    {
        return $this->userGet($username, 'lastlogin');
    }

    /**
     * Update lastlogin for a single user
     * 
     * @param  string $username User key
     * @param  string $value    New value
     * @return void
     */
    public function setLastLogin($username, $value)
    {
        $this->userSet($username, $value, 'lastlogin');
    }


    /**
     * Update password for a single user
     * 
     * @param  string $username User key
     * @param  string $value    New password
     * @return void
     */
    public function setPassword($username, $value)
    {
        $value = $this->crypto->hashString($value);
        $this->userSet($username, $value, 'password');
    }

    /**
     * Validate value against current password for a user
     * 
     * @param  string  $username User key
     * @param  string  $value    password to check
     * @return boolean
     */
    public function validatePassword($username, $value)
    {
        $hash = $this->userGet($username, 'password');
        if (!$hash) {
            return false;
        }

        $valid = $this->crypto->compareHash($value, $hash);
        if ($valid && $this->crypto->hashNeedsUpdating($hash)) {
            $this->setPassword($username, $value);
        }

        return $valid;
    }


    /**
     * Get all permissions for a user
     * 
     * @param  string     $username User key
     * @return array|null
     */
    public function getPermissions($username)
    {
        $this->rolesModel->setParameters(['username' => $username]);
        return $this->rolesModel->permissions;
    }


    /**
     * Get photo for a user
     * 
     * @param  string      $username User key
     * @return string|null
     */
    public function getPhoto($username)
    {
        $path = $this->photoHandler->Read($this->getPhotoKey($username));

        return ($path) ? $this->photoHandler->makeRelative($path) : null;
    }

    /**
     * Update photo for a user
     * 
     * @param  string $username User key
     * @param  mixed  $value    Null to just delete, array or string to set
     * @return void
     */
    public function setPhoto($username, $value)
    {
        if ($value) {
            $this->photoHandler->Write($this->getPhotoKey($username), $value);    
        } else {
            $this->photoHandler->Delete($this->getPhotoKey($username));
        }       
    }

    /**
     * Validates uploaded image for use
     *
     * @see \rakelley\jhframe\interfaces\IFileHandler::Validate()
     */
    public function validatePhoto(array $file)
    {
        return $this->photoHandler->Validate($file);
    }

    /**
     * Internal method for retrieving image key for user
     * 
     * @param  string $username User key
     * @return string
     */
    protected function getPhotoKey($username)
    {
        return str_replace(' ', '_', $this->userGet($username, 'fullname'));
    }


    /**
     * Get profile for a user
     * 
     * @param  string      $username User key
     * @return string|null
     */
    public function getProfile($username)
    {
        return $this->userGet($username, 'profile');
    }

    /**
     * Update profile for a user
     * 
     * @param  string $username User key
     * @param  string $value    New value
     * @return void
     */
    public function setProfile($username, $value)
    {
        $this->userSet($username, $value, 'profile');
    }


    /**
     * Get roles for a user
     * 
     * @param  string     $username User key
     * @return array|null
     */
    public function getRoles($username)
    {
        $this->rolesModel->setParameters(['username' => $username]);
        return $this->rolesModel->roles;
    }

    /**
     * Update roles for a user
     * 
     * @param  string     $username User key
     * @param  array|null $value    New value
     * @return void
     */
    public function setRoles($username, $value)
    {
        $this->rolesModel->setParameters(['username' => $username]);
        $this->rolesModel->roles = $value;
    }


    /**
     * Internal reusable method for getting a property from the userModel
     * 
     * @param  string $username User key
     * @param  string $property Property name
     * @return mixed
     */
    protected function userGet($username, $property)
    {
        $this->userModel->setParameters(['username' => $username]);
        return $this->userModel->$property;
    }

    /**
     * Internal reusable method for setting a property from the userModel
     * 
     * @param  string $username User key
     * @param  mixed  $value    New value
     * @param  string $property Property name
     * @return void
     */
    protected function userSet($username, $value, $property)
    {
        $this->userModel->setParameters(['username' => $username]);
        $this->userModel->$property = $value;
    }
}
