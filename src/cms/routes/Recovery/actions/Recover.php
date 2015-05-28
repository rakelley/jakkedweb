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

namespace cms\routes\Recovery\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for performing account recovery with a valid token
 */
class Recover extends \rakelley\jhframe\classes\FormAction
{
    /**
     * Mail service instance
     * @var object
     */
    private $mail;
    /**
     * Recovery repo instance
     * @var object
     */
    private $tokens;
    /**
     * UserAccount repo instance
     * @var object
     */
    private $userAccount;


    /**
     * @param \cms\routes\Recovery\views\RecoverForm               $view
     * @param \cms\repositories\Recovery                           $tokens
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \rakelley\jhframe\interfaces\services\IMail          $mail
     * @param \main\repositories\UserAccount                       $userAccount
     */
    function __construct(
        \cms\routes\Recovery\views\RecoverForm $view,
        \cms\repositories\Recovery $tokens,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \rakelley\jhframe\interfaces\services\IMail $mail,
        \main\repositories\UserAccount $userAccount
    ) {
        $this->tokens = $tokens;
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
        $this->userAccount->setPassword($this->input['username'],
                                        $this->input['new']);
        $this->sendMail();

        $this->tokens->Delete($this->input['username']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        $this->tokens->Validate($this->input['username'],
                                $this->input['token']);
    }


    /**
     * Sends confirmation mail to user on success
     * 
     * @return void
     */
    private function sendMail()
    {
        $title = 'Jakked CMS Password Recovery Successful';
        $body = <<<HTML
<p>The password for your Jakked Hardcore Gym CMS account has been successfully
    reset. If you were not the one to do this, verify the security of your
    e-mail account and then request another password reset.
</p>
HTML;

        $this->mail->Send($this->input['username'], $title, $body);
    }
}
