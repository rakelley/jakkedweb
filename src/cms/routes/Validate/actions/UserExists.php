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

namespace cms\routes\Validate\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * Action to check if user matching provided username exists
 */
class UserExists extends \rakelley\jhframe\classes\Action
{
    use \rakelley\jhframe\traits\GetsInput,
        \rakelley\jhframe\traits\ServiceLocatorAware;

    /**
     * UserAccount repo instance
     * @var object
     */
    private $userAccount;


    /**
     * @param \main\repositories\UserAccount $userAccount
     */
    function __construct(
        \main\repositories\UserAccount $userAccount
    ) {
        $this->userAccount = $userAccount;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $requires = ['username' => 'default'];

        try {
            $input = $this->getInput($requires, 'post');
            $result = $this->userAccount->userExists($input['username']);
        } catch (InputException $e) {
            $this->error = $e->getMessage();
            $result = false;
        }

        return $result;
    }
}
