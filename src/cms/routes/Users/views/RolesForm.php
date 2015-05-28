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

namespace cms\routes\Users\views;

/**
 * FormView for setting a user's roles
 */
class RolesForm extends \rakelley\jhframe\classes\FormView implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    use \rakelley\jhframe\traits\TakesParameters;

    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$fields
     */
    protected $fields = [
        'username' => [
            'type' => 'hidden',
            'attr' => [
                'name' => 'username',
            ],
            'data-binding' => 'username',
            'required' => true,
            'sanitize' => 'default',
        ],
        'roles' => [
            'method' => 'fillRoles',
        ],
        'submit' => [
            'type' => 'submit',
            'attr' => [
                'value' => 'Update Roles',
                'class' => 'button_safe',
            ],
        ],
    ];
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\FormView::$attributes
     */
    protected $attributes = [
        'action'          => 'users/roles',
        'method'          => 'post',
        'data-valmethods' => 'redirect-hide',
        'data-redirect'   => 'users/',
    ];
    /**
     * Roles repo instance
     * @var object
     */
    protected $roles;
    /**
     * UserAccount repo instance
     * @var object
     */
    protected $userAccount;


    function __construct(
        \cms\repositories\Roles $roles,
        \rakelley\jhframe\interfaces\services\IFormBuilder $builder,
        \main\repositories\UserAccount $userAccount
    ) {
        $this->roles = $roles;
        $this->userAccount = $userAccount;

        parent::__construct($builder);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data['roles'] = $this->roles->getAll();
        $this->data['relations'] = $this->roles->getRelations();
        $this->data['userRoles'] =
            $this->userAccount->getRoles($this->parameters['username']);
    }


    /**
     * Custom method for roles field
     * 
     * @return string
     */
    protected function fillRoles()
    {
        $userRoles = $this->data['userRoles'];
        $relations = $this->data['relations'];

        return implode('', array_map(
            function($role) use ($userRoles, $relations) {
                $name = $role['role'];

                if ($userRoles && in_array($name, $userRoles)) {
                    $checkedProp = ' checked';
                } else {
                    $checkedProp = '';
                }

                if (isset($relations[$name])) {
                    $disableProp = ' data-disablesRelated="' .
                                   implode(',', $relations[$name]) .
                                   '"';
                } else {
                    $disableProp = '';
                }

                return <<<HTML
<label>
    <input type="checkbox" id="{$name}" name="{$name}"
        value="{$name}"{$checkedProp}{$disableProp} />
    {$role['description']}
</label>

HTML;
            },
            $this->data['roles']
        ));
    }
}
