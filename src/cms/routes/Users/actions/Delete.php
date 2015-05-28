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

namespace cms\routes\Users\actions;

use \rakelley\jhframe\classes\InputException;

/**
 * FormAction for deleting a user
 */
class Delete extends \rakelley\jhframe\classes\FormAction
{
    /**
     * Articles repo instance
     * @var object
     */
    private $articles;
    /**
     * UserAccount repo instance
     * @var object
     */
    private $userAccount;


    /**
     * @param \cms\routes\Users\views\DeleteForm                   $view
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \main\repositories\Article                           $articles
     * @param \main\repositories\UserAccount                       $userAccount
     */
    function __construct(
        \cms\routes\Users\views\DeleteForm $view,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \main\repositories\Article $articles,
        \main\repositories\UserAccount $userAccount
    ) {
        $this->articles = $articles;
        $this->userAccount = $userAccount;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->articles->revertAuthor($this->input['username']);

        $this->userAccount->deleteUser($this->input['username']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     */
    protected function validateInput()
    {
        if (!$this->userAccount->userExists($this->input['username'])) {
            throw new InputException('User Not Found');
        }
    }
}
