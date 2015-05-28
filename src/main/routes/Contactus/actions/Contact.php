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
namespace main\routes\Contactus\actions;

use \rakelley\jhframe\interfaces\services\IMail;

/**
 * Action for ContactForm
 */
class Contact extends \rakelley\jhframe\classes\FormAction
{
    use \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\action\ValidatesBotcheckField;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::$touchesData
     */
    protected $touchesData = false;
    /**
     * Mail service instance
     * @var object
     */
    private $mail;


    /**
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \rakelley\jhframe\interfaces\services\IMail          $mail
     * @param \main\routes\Contactus\views\ContactForm             $view
     */
    function __construct(
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \rakelley\jhframe\interfaces\services\IMail $mail,
        \main\routes\Contactus\views\ContactForm $view
    ) {
        $this->mail = $mail;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $title = 'New Website Message from '. $this->input['name'];

        $body = <<<HTML
<p>
    You have received a new message from the contact form on your website.
</p>
<p><strong>Name: </strong>{$this->input['name']}</p>
<p><strong>Email Address: </strong>{$this->input['email']}</p>
<p><strong>Message: </strong>{$this->input['textarea']}</p>

HTML;

        $this->mail->Send(IMail::ACCOUNT_MAIN, $title, $body,
                          $this->input['email']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        $this->validateBotcheckField();
    }
}
