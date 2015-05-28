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

/******************** PRODUCTION CONFIGURATION FOR MAIN APP *******************/
$prefix = dirname(__FILE__) . DIRECTORY_SEPARATOR . '_production-';

$appConfig = require($prefix . 'app.php');

$classes = json_decode(file_get_contents($prefix . 'classes.json'), true);

$envConfig = require($prefix . 'env.php');

$phpConfig = require($prefix . 'php.php');

$secrets = json_decode(file_get_contents($prefix . 'secrets.json'), true);


return [
    'APP'     => $appConfig,
    'CLASSES' => $classes,
    'ENV'     => $envConfig,
    'PHP'     => $phpConfig,
    'SECRETS' => $secrets,
];
