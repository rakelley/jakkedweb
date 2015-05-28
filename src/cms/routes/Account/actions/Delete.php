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
 * Action for users to delete their account
 */
class Delete extends \rakelley\jhframe\classes\FormAction implements
    \rakelley\jhframe\interfaces\ITakesParameters
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * Article repo instance
     * @var object
     */
    private $article;
    /**
     * UserAuth repo instance
     * @var object
     */
    private $auth;
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
     * @param \cms\routes\Account\views\DeleteForm                 $view
     * @param \cms\repositories\UserAuth                           $auth
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \rakelley\jhframe\interfaces\services\IMail          $mail
     * @param \main\repositories\Article                           $article
     * @param \main\repositories\UserAccount                       $userAccount
     */
    function __construct(
        \cms\routes\Account\views\DeleteForm $view,
        \cms\repositories\UserAuth $auth,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \rakelley\jhframe\interfaces\services\IMail $mail,
        \main\repositories\Article $article,
        \main\repositories\UserAccount $userAccount
    ) {
        $this->auth = $auth;
        $this->mail = $mail;
        $this->article = $article;
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
        /* We need to manually revert articles written by this user because
          MySql doesn't support "on delete set default"*/
        $this->article->revertAuthor($this->parameters['username']);

        $this->userAccount->deleteUser($this->parameters['username']);
        $this->sendMail();
        $this->auth->logOut();
    }


    /**
     * Sends mail to user on success
     * 
     * @return void
     */
    private function sendMail()
    {
        $title = 'Jakked CMS Account Deleted';
        $body = <<<HTML
<p>Your Jakked CMS Account has been successfully deleted.  Please contact an
    Admin immediately if you were not the one who performed this action.
</p>
HTML;
        $this->mail->Send($this->parameters['username'], $title, $body);
    }
}
