<?php
defined('JHFRAME_ROOTDIR') ||
    define('JHFRAME_ROOTDIR', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
set_include_path(JHFRAME_ROOTDIR);
require_once('vendor/autoload.php');

$bootstrapper = new \rakelley\jhframe\classes\Bootstrapper;

$args = [
    'hostname' => 'https://jakkedhardcore.com/',
];
$app = $bootstrapper->Bootstrap('main', $args);

$app->serveRequest();
