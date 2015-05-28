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
 * Composite view for editing a user
 */
class Edit extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\ITakesParameters,
    \rakelley\jhframe\interfaces\view\IRequiresData,
    \rakelley\jhframe\interfaces\view\IHasSubViews
{
    use \rakelley\jhframe\traits\GetCalledNamespace,
        \rakelley\jhframe\traits\ServiceLocatorAware,
        \rakelley\jhframe\traits\TakesParameters,
        \rakelley\jhframe\traits\view\MakesSubViews;

    /**
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * UserAccount repo instance
     * @var object
     */
    private $user;


    /**
     * @param \main\repositories\UserAccount $user
     */
    function __construct(
        \main\repositories\UserAccount $user
    ) {
        $this->user = $user;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data['photo'] =
            $this->user->getPhoto($this->parameters['username']);
        $this->data['profile'] =
            $this->user->getProfile($this->parameters['username']);
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $photo = (!$this->data['photo']) ? '' :
                 $this->fillPhoto($this->data['photo']);
        $profile = (!$this->data['profile']) ? '' :
                   $this->fillProfile($this->data['profile']);

        $this->viewContent = <<<HTML
<h2 class="page-heading">Editing {$this->parameters['username']}</h2>

{$this->subViews['delete']}

{$this->subViews['roles']}

<div class="column-onethird">
    {$photo}
</div>
<div class="column-onethird">
    {$profile}
</div>

<script data-main="js/src/cms/main" data-page="lib/disableRelatedInputs"
    src="js/require.js"></script>

HTML;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\traits\view\MakesSubViews::getSubViewList()
     */
    protected function getSubViewList()
    {
        return [
            'roles' => 'RolesForm',
            'delete' => 'DeleteForm',
        ];
    }


    /**
     * Generates markup for user's photo
     * 
     * @param  string $photo Relative path of photo
     * @return string
     */
    private function fillPhoto($photo)
    {
        return <<<HTML
<h4>Photo: </h4>
<img class="image-user" src="/{$photo}" />

HTML;
    }


    /**
     * Generates markup for user's profile
     * 
     * @param  string $profile Body of profile
     * @return string
     */
    private function fillProfile($profile)
    {
        return '<h4>Profile: </h4>' . $profile;
    }
}
