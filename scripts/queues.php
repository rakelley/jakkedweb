<?php
/**
 * Cron nightly queue mailer
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


$articles = count($locator->Make('cms\repositories\ArticleQueue')
                          ->getAll());

$tests = count($locator->Make('main\repositories\TestimonialQueue')
                       ->getAll());

$queue = $articles + $tests;

if ($queue) {
    $mail = $locator->Make('rakelley\jhframe\interfaces\services\IMail');

    $title = 'New CMS Queue Items Waiting';
    $body = <<<TXT
There are currently {$queue} items in one or more CMS queues. Please log into
the Jakked Hardcore Gym CMS to approve or reject them.
TXT;

    $mail->Send($mail::ACCOUNT_ADMIN, $title, $body);
}
