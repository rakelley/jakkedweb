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

use \rakelley\jhframe\interfaces\services\IExceptionHandler;

// Dev-Only For Debugging
if (!function_exists('preprint')) {
    function preprint($input, $return=false)
    {
        $output = '<pre>' . print_r($input, 1) . '</pre>';
        if ($return) {
            return $output;
        } else {
            print $output;
        }
    }
}

/******************* DEVELOPMENT CONFIGURATION FOR MAIN APP *******************/
$config = require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'production.php');
$prefix =  dirname(__FILE__) . DIRECTORY_SEPARATOR . '_development-';

$config['APP']['exception_log_level'] = IExceptionHandler::LOGGING_ALL;

$config['ENV']['type'] = 'development';

$config['PHP']['display_errors'] = 1;

$config['SECRETS'] = json_decode(file_get_contents($prefix . 'secrets.json'),
                                 true);


return $config;
