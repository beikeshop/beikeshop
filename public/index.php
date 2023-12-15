<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

$basePath       = getcwd() . '/';
$htaPath        = $basePath . '.htaccess';
$htaExamplePath = $basePath . 'htaccess.txt';
if (! file_exists($htaPath)) {
    if (file_exists($htaExamplePath)) {
        copy($htaExamplePath, $htaPath);
    } else {
        touch($htaPath);
    }
}

if (! file_exists(__DIR__ . '/../storage/installed')
    && ! (isset($_SERVER['REQUEST_URI']) && substr($_SERVER['REQUEST_URI'], 0, 10) == '/installer')
    && (stripos($_SERVER['REQUEST_URI'], '_debugbar') !== 1)) {
    header('Location: /installer');
    exit;
}

if (version_compare(PHP_VERSION, '8.1', '<')) {
    echo 'Your current version of PHP does not meet the requirements. Upgrade to at least version 8.1 <br/>';
    echo '您当前PHP版本不满足要求，至少升级至 8.1 版本';
    exit;
}

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists(__DIR__ . '/../storage/framework/maintenance.php')) {
    require __DIR__ . '/../storage/framework/maintenance.php';
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
