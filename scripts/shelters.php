<?php
/**
 * Cron nightly animal shelters updater
 */
defined('JHFRAME_ROOTDIR') ||
    define('JHFRAME_ROOTDIR', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
set_include_path(JHFRAME_ROOTDIR);
require_once('vendor/autoload.php');

$bootstrapper = new \rakelley\jhframe\classes\Bootstrapper;

$args = [
    'hostname' => 'https://cms.jakkedhardcore.com/',
];
$locator = $bootstrapper->Bootstrap('cms', $args)
                        ->getLocator();


$controllerClass = 'rakelley\jhframe\interfaces\services\IActionController';
$actionClass = 'cms\routes\Animals\actions\Update';
$mailInterface = 'rakelley\jhframe\interfaces\services\IMail';

$result = $locator->Make($controllerClass)
                  ->executeAction($actionClass);

if (!$result->getSuccess()) {
    $date = date(DATE_ISO8601);
    $mail = $locator->Make($mailInterface);

    $title = 'Nightly Animal Update Failed';
    $body = <<<TXT
Shelter Update failed at {$date}.  Please check logs if this error persists.
TXT;

    $mail->Send($mail::ACCOUNT_ADMIN, $title, $body);
}
