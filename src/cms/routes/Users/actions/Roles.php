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
 * FormAction for setting a user's roles
 */
class Roles extends \rakelley\jhframe\classes\FormAction
{
    /**
     * IIo service instance
     * @var object
     */
    private $io;
    /**
     * Roles repo instance
     * @var object
     */
    private $roles;
    /**
     * UserAccount repo instance
     * @var object
     */
    private $userAccount;


    /**
     * @param \cms\routes\Users\views\RolesForm                    $view
     * @param \cms\repositories\Roles                              $roles
     * @param \rakelley\jhframe\interfaces\services\IFormValidator $validator
     * @param \rakelley\jhframe\interfaces\services\IIo            $io
     * @param \main\repositories\UserAccount                       $userAccount
     */
    function __construct(
        \cms\routes\Users\views\RolesForm $view,
        \cms\repositories\Roles $roles,
        \rakelley\jhframe\interfaces\services\IFormValidator $validator,
        \rakelley\jhframe\interfaces\services\IIo $io,
        \main\repositories\UserAccount $userAccount
    ) {
        $this->roles = $roles;
        $this->io = $io;
        $this->userAccount = $userAccount;

        parent::__construct($validator, $view);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\action\IAction::Proceed()
     */
    public function Proceed()
    {
        $this->userAccount->setRoles($this->input['username'],
                                     $this->input['roles']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormAction::validateInput()
     *
     * @todo validation should be using input->searchKeys instead of Io, and not
     * need Roles repo, however js-disabling setup currently preventing easy key
     * alteration
     */
    protected function validateInput()
    {
        if (!$this->userAccount->userExists($this->input['username'])) {
            throw new InputException('User Not Found');
        }

        $all = array_column($this->roles->getAll(), 'role');
        $submitted = array_values($this->io->getInputTable('post'));
        $this->input['roles'] = array_intersect($submitted, $all);
    }
}
