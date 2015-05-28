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

namespace cms\routes\Account\actions;

/**
 * Action for users to change their account photo
 */
class ChangePhoto extends \rakelley\jhframe\classes\FormAction implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * UserAccount repo instance
     * @var object
     */
    private $userAccount;


    /**
     * @param \cms\routes\Account\views\PhotoForm                  $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\UserAccount                       $userAccount
     */
    function __construct(
        \cms\routes\Account\views\PhotoForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\UserAccount $userAccount
    ) {
        $this->userAccount = $userAccount;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     * @return void
     */
    public function Proceed()
    {
        $this->userAccount->setPhoto($this->parameters['username'],
                                     $this->input['photo']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        $this->userAccount->validatePhoto($this->input['photo']);
    }
}
