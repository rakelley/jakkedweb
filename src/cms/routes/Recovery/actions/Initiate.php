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
 * FormAction for initiating account recovery, generates token and emails user
 */
class Initiate extends \rakelley\jhframe\classes\FormAction implements
    \rakelley\jhframe\interfaces\action\IHasResult
{
    use \cms\SpamCheckActionTrait,
        \rakelley\jhframe\traits\ConfigAware;

    /**
     * Site base url, pulled from config
     * @var string
     */
    private $basePath;
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
     * @param \cms\routes\Recovery\views\InitiateForm              $view
     * @param \cms\repositories\Recovery                           $tokens
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \rakelley\jhframe\interfaces\services\IMail          $mail
     * @param \main\repositories\UserAccount                       $userAccount
     */
    function __construct(
        \cms\routes\Recovery\views\InitiateForm $view,
        \cms\repositories\Recovery $tokens,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \rakelley\jhframe\interfaces\services\IMail $mail,
        \main\repositories\UserAccount $userAccount
    ) {
        $this->tokens = $tokens;
        $this->mail = $mail;
        $this->userAccount = $userAccount;

        $this->basePath = $this->getConfig()->Get('APP', 'base_path');

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     * @return void
     */
    public function Proceed()
    {
        $token = $this->tokens->Create($this->input['username']);

        $this->sendMail($token);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IHasResult::getResult()
     * @return string
     */
    public function getResult()
    {
        return <<<HTML
<h4 class="alignment-center">Success, please check your email to continue.</h4>
HTML;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        $this->validateSpamCheck($this->input['spamcheck']);

        if (!$this->userAccount->userExists($this->input['username'])) {
            throw new InputException('Account Not Found');
        }
    }


    /**
     * Sends recovery email to user with generated token
     * 
     * @param  string $token Token for use in recovering account
     * @return void
     */
    private function sendMail($token)
    {
        $url = $this->basePath . 'recovery/index?token=' . $token;
        $title = 'Jakked CMS Password Recovery';
        $body = <<<HTML
<p>Someone has requested a password reset for your Jakked Hardcore Gym CMS
    account.  If this was not you, ignore this email and no changes will be
    made.  If you were the one who made this request, follow
    <a href="{$url}">this link</a> to complete the process.
</p>
<p>The link is only valid for 24 hours.</p>
HTML;

        $this->mail->Send($this->input['username'], $title, $body);
    }
}
