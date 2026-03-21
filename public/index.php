<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Hostinger: Resolve the actual Laravel app root
|--------------------------------------------------------------------------
| On Hostinger, public_html is separate from the app directory.
| We check if we're running from public_html and adjust the base path.
*/
$appRoot = realpath(__DIR__.'/..') ?: __DIR__.'/..';

// If running from public_html on Hostinger, point to the actual app directory
if (str_contains(__DIR__, 'public_html')) {
    $appRoot = dirname(__DIR__); // /home/.../domains/learn.ajayupadhyay.in
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appRoot.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appRoot.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $appRoot.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
