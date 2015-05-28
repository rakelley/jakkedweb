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

use \rakelley\jhframe\classes\InputException,
    \rakelley\jhframe\interfaces\services\IMail;

/**
 * Action for creating a new user account
 */
class Create extends \rakelley\jhframe\classes\FormAction
{
    use \cms\SpamCheckActionTrait,
        \rakelley\jhframe\traits\ConfigAware;

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
     * @param \cms\routes\Account\views\CreateForm                 $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \rakelley\jhframe\interfaces\services\IMail          $mail
     * @param \main\repositories\UserAccount                       $userAccount
     */
    function __construct(
        \cms\routes\Account\views\CreateForm $view,
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
     * @return void
     */
    public function Proceed()
    {
        $this->userAccount->addUser($this->input);

        $this->mailUser();
        $this->mailAdmins();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     * @throws \rakelley\jhframe\classes\InputException
     */
    protected function validateInput()
    {
        $this->validateSpamCheck($this->input['spamcheck']);

        if ($this->userAccount->userExists($this->input['username'])) {
            throw new InputException('Account Already Exists');
        }
    }


    /**
     * Sends mail to user on success
     * 
     * @return void
     */
    private function mailUser()
    {
        $title = 'Thank You for Registering';
        $body = <<<HTML
<p>Thank you for registering at 
    <a href="//jakkedhardcore.com">jakkedhardcore.com</a>. Your
    registration is complete but you will be unable to access any CMS features
    until an administrator reviews your registration and assigns appropriate
    permissions. This may take several days.
</p>
<p>In the mean time, you may wish to 
    <a href="https://cms.jakkedhardcore.com/">log in</a> to upload a user image
    and fill out your public profile by clicking the "My Account" link. These
    will be what site visitors see about you if you are an author or personal
    trainer.
</p>
HTML;

        $this->mail->Send($this->input['username'], $title, $body);
    }


    /**
     * Sends mail to all admins on success
     * 
     * @return void
     */
    private function mailAdmins()
    {
        $title = 'A New User Has Registered';
        $body = <<<TXT
A new user, {$this->input['username']}, has registered at jakkedhardcore.com.
Please log in to CMS management to set user permissions as appropriate. This is
an automated message sent to all User Administrators.
TXT;

        $this->mail->Send(IMail::ALL_ADMIN_ACCOUNTS, $title, $body);
    }
}
