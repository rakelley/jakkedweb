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

use \rakelley\jhframe\classes\InputException;

/**
 * Action for user login
 */
class Login extends \rakelley\jhframe\classes\FormAction
{
    /**
     * AuthService instance
     * @var object
     */
    private $authService;
    /**
     * UserAccount repo instance
     * @var object
     */
    private $userAccount;


    /**
     * @param \cms\routes\Auth\views\LoginForm                     $view
     * @param \rakelley\jhframe\interfaces\services\IAuthService   $authService
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\UserAccount                       $userAccount
     */
    function __construct(
        \cms\routes\Auth\views\LoginForm $view,
        \rakelley\jhframe\interfaces\services\IAuthService $authService,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\UserAccount $userAccount
    ) {
        $this->authService = $authService;
        $this->userAccount = $userAccount;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->authService->logIn($this->input['username']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if (!$this->userAccount->validatePassword($this->input['username'],
                                                  $this->input['password'])
        ) {
            throw new InputException(
                'Username Does Not Exist or Password Does Not Match'
            );
        }
    }
}
