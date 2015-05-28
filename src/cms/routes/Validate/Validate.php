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

namespace cms\routes\Validate;

/**
 * RouteController handling validation methods for form inputs,
 * ajax-called by client-side library
 */
class Validate extends \rakelley\jhframe\classes\RouteController
{
    /**
     * {@inheritdoc}
     * @see \rakelley\jhframe\classes\RouteController::$routes
     */
    protected $routes = [
        'post' => [
            '/(date)\b/'      => 'date',
            '/datetime/'      => 'dateTime',
            '/pagenotexists/' => 'pageNotExists',
            '/spamcheck/'     => 'spamCheck',
            '/userexists/'    => 'userExists',
            '/usernotexists/' => 'userNotExists',
        ],
    ];


    /**
     * Validates a date string
     */
    public function Date()
    {
        $action = 'rakelley\jhframe\classes\ArgumentValidator';
        $arguments = [
            'requires' => [
                'date' => ['filters' => ['date' => 'Y-m-d']],
            ],
            'method' => 'post',
        ];

        $result = $this->actionController->executeAction($action, $arguments);
        $content = ($result->getSuccess()) ?:
                   'Field must be a valid "YYYY-MM-DD" Date';

        $result->setContent($content)
               ->Render();
    }


    /**
     * Validates a date time string
     */
    public function dateTime()
    {
        $action = 'rakelley\jhframe\classes\ArgumentValidator';
        $arguments = [
            'requires' => [
                'date' => ['filters' => ['date' => 'Y-m-d H:i:s']],
            ],
            'method' => 'post',
        ];

        $result = $this->actionController->executeAction($action, $arguments);
        $content = ($result->getSuccess()) ?:
                   'Field must be a valid "YYYY-MM-DD HH:MM:SS" Date';

        $result->setContent($content)
               ->Render();
    }


    /**
     * Validates a page does not exist
     */
    public function pageNotExists()
    {
        $action = __NAMESPACE__ . '\actions\PageNotExists';

        $result = $this->actionController->executeAction($action);
        $content = ($result->getSuccess()) ?:
                   'A Page With That Name Already Exists';

        $result->setContent($content)
               ->Render();
    }


    /**
     * Validates a spamcheck challenge
     */
    public function spamCheck()
    {
        $action = __NAMESPACE__ . '\actions\SpamCheck';

        $result = $this->actionController->executeAction($action);
        $content = ($result->getSuccess()) ?: 'Your Answer Is Incorrect';

        $result->setContent($content)
               ->Render();
    }


    /**
     * Validates a user exists
     */
    public function userExists()
    {
        $action = __NAMESPACE__ . '\actions\UserExists';

        $result = $this->actionController->executeAction($action);
        $content = ($result->getSuccess()) ?: 'That Username Does Not Exist';

        $result->setContent($content)
               ->Render();
    }


    /**
     * Validates a user does not exist
     */
    public function userNotExists()
    {
        $action = __NAMESPACE__ . '\actions\UserNotExists';

        $result = $this->actionController->executeAction($action);
        $content = ($result->getSuccess()) ?: 'That Username Already Exists';

        $result->setContent($content)
               ->Render();
    }
}
