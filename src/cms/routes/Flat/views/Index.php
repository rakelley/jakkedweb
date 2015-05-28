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

namespace cms\routes\Flat\views;

/**
 * Composite View for landing page when not logged in
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IHasSubViews
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\view\MakesSubViews;


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $this->viewContent = <<<HTML
{$this->subViews['login']}

<div class="alignment-center">
    <button data-modal="#register_dialog">
        Register
    </button>
    or
    <button data-modal="#recover_dialog">
        Recover Lost Password
    </button>
</div>

<div data-modal-window class="modal" id="register_dialog"
    title="Register a New Account">
    {$this->subViews['create']}
</div>

<div data-modal-window class="modal" id="recover_dialog"
    title="Recover Your Password">
    {$this->subViews['recovery']}
</div>

<script data-main="js/src/cms/main" data-page="cms/routes/Flat/index"
src="js/require.js">
</script>

HTML;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\traits\view\MakesSubViews::getSubViewList()
     */
    protected function getSubViewList()
    {
        return [
            'login' => 'cms\routes\Auth\views\LoginForm',
            'create' => 'cms\routes\Account\views\CreateForm',
            'recovery' => 'cms\routes\Recovery\views\InitiateForm',
        ];
    }
}
