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
 * Trait for RouteControllers which need to get a property of the currently
 * logged in user in order to pass to a route method
 */
trait UserSpecificRoutes
{
    /**
     * Interface for AuthService
     * @var string
     */
    protected $authServiceInterface =
        '\rakelley\jhframe\interfaces\services\IAuthService';


    /**
     * ServiceLocator getter, can be implemented through
     * \rakelley\jhframe\traits\ServiceLocatorAware
     */
    abstract protected function getLocator();


    /**
     * Gets property of currently logged in user
     * 
     * @param  string $property Property name
     * @return mixed
     */
    protected function getUserProperty($property)
    {
        return $this->getLocator()->Make($this->authServiceInterface)
                                  ->getUser($property);
    }
}
