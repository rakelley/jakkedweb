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
 * Composite view displaying all users
 */
class Index extends \rakelley\jhframe\classes\View implements
    \rakelley\jhframe\interfaces\view\IRequiresData
{
    /**
     * Store for fetched data
     * @var array
     */
    private $data;
    /**
     * UserAccount repo instance
     * @var object
     */
    private $users;


    /**
     * @param \main\repositories\UserAccount $users
     */
    function __construct(
        \main\repositories\UserAccount $users
    ) {
        $this->users = $users;
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IRequiresData::fetchData()
     */
    public function fetchData()
    {
        $this->data = $this->users->getAll();
    }


    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\interfaces\view\IView::constructView()
     */
    public function constructView()
    {
        $users = implode('', array_map([$this, 'fillUser'], $this->data));

        $this->viewContent = <<<HTML
<h2 class="page-heading">Edit CMS Users</h2>

<table>
    <thead>
        <tr>
            <th>Edit User</th>
            <th>Username</th>
            <th>Full Name</th>
            <th>Last Login</th>
        </tr>
    </thead>
    <tbody>
        {$users}
    </tbody>
</table>

HTML;
    }


    /**
     * Generates markup for a single user's row
     * 
     * @param  array  $user User properties
     * @return string
     */
    private function fillUser($user)
    {
        return <<<HTML
<tr>
    <td data-th="Edit User">
        <a class="button" href="/users/edit?username={$user['username']}">
            Edit
        </a>
    </td>
    <td data-th="Username">{$user['username']}</td>
    <td data-th="Full Name">{$user['fullname']}</td>
    <td data-th="Last Login">{$user['lastlogin']}</td>
</tr>

HTML;
    }
}
