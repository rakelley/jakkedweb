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

use \rakelley\jhframe\interfaces\services\IExceptionHandler;

if (isset($_SERVER['HTTP_HOST'])) {
    if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === 443)
    ) {
        $connectionType = 'https://';
    } else {
        $connectionType = 'http://';
    }

    $basePath = $connectionType . $_SERVER['HTTP_HOST'] . '/';
} else { //fallback for HTTP 1.0 and Local
    $basePath = 'https://cms.jakkedhardcore.com';
}


return [
    'name'                => 'cms',
    'base_path'           => $basePath,
    'email_admin'         => 'admin@jakkedhardcore.com',
    'email_main'          => 'gym@jakkedhardcore.com',
    'exception_log_level' => IExceptionHandler::LOGGING_ALL,
    'force_https'         => true,
    'input_rules'         => [
        'email' => [
            'filters' => 'email',
        ],
        'name' => [
            'filters' => [
                'word' => '\s',
            ],
        ],
        'textarea' => [
            'filters' => 'plainText',
            'minlength' => 20,
        ],
        'page' => [
            'filters' => 'int',
            'defaultvalue' => 1,
        ],
        'template' => [
            'filters' => [
                'word',
                'strtolower',
            ],
        ],
        'username' => [
            'filters' => [
                'email',
                'strtolower',
            ],
        ],
        'token' => [
            'filters' => 'word',
            'minlength' => 20,
            'maxlength' => 20,
        ],
        'id' => [
            'filters' => 'int',
        ],
        'title' => [
            'filters' => 'plainText',
            'minlength' => 20,
            'maxlength' => 200,
        ],
        'tags' => [
            'filters' => [
                'aslist' => [
                    'separator' => ',',
                    'filters'   => [
                        'word',
                        'strtolower',
                    ],
                ],
            ],
        ],
        'date' => [
            'filters' => 'date',
        ],
        'description' => [
            'filters' => 'plainText',
            'minlength' => 20,
        ],
        'route' => [
            'filters' => [
                'rawurldecode',
                'strtolower',
                'word' => '\/',
            ],
        ],
    ],
];
