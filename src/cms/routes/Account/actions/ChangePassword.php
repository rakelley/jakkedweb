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

use \rakelley\jhframe\classes\InputException;

/**
 * Action for a user to change their password
 */
class ChangePassword extends \rakelley\jhframe\classes\FormAction implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * Mail service instance
     * @var object
     */
    private $mail;
    /**
     * UserAccount repo instance
     * @var object
     */
    private $userAccount;


    /**
     * @param \cms\routes\Account\views\PasswordForm               $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \rakelley\jhframe\interfaces\services\IMail          $mail
     * @param \main\repositories\UserAccount                       $userAccount
     */
    function __construct(
        \cms\routes\Account\views\PasswordForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \rakelley\jhframe\interfaces\services\IMail $mail,
        \main\repositories\UserAccount $userAccount
    ) {
        $this->mail = $mail;
        $this->userAccount = $userAccount;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->userAccount->setPassword($this->parameters['username'],
                                        $this->input['new']);
        $this->sendMail();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if (!$this->userAccount->validatePassword($this->parameters['username'],
                                                  $this->input['old'])
        ) {
            throw new InputException(
                'Account Does Not Exist or Password Does Not Match'
            );
        }
    }


    /**
     * Sends email to user informing of change
     * 
     * @return void
     */
    private function sendMail()
    {
        $title   = 'Jakked CMS Password Changed';
        $body    = <<<HTML
<p>The password for your Jakked CMS account was just successfully changed.  If
    this was not you, please verify the security of your computer and email
    account and then use the password recovery tool or notify an Admin.
</p>
HTML;
        $this->mail->Send($this->parameters['username'], $title, $body);
    }
}
