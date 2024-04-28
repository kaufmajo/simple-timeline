<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

// -------------------------
// start custom bootstrap code
// -------------------------

const REFRESH_STATIC_FILES = '15';

function ddd(mixed $var): never
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die("ddd");
}

// set timezone
date_default_timezone_set('Europe/Zurich');

// define application environment
if (
    isset($_SERVER['HTTP_HOST']) &&
    ($_SERVER['HTTP_HOST'] === 'jk1.development.net:8889')
) {
    // error handling for development
    error_reporting(E_ALL & ~E_USER_DEPRECATED & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
    ini_set("display_errors", "1"); // oder 1 = on / 0 = off

    // app settings for development
    define('APPLICATION_ENV', 'development');
    define('APPLICATION_HOST', 'jk1.development.net');
    setlocale(LC_TIME, "de_CH.UTF8");
} else {
    // error handling for development
    error_reporting(~E_USER_DEPRECATED & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
    //ini_set("display_errors", "1"); // oder 1 = on / 0 = off

    // app settings for production
    define('APPLICATION_ENV', 'production');
    define('APPLICATION_HOST', 'termine.egli.church');
    setlocale(LC_TIME, "de_DE.ISO8859-1");
}

// -------------------------
// end custom bootstrap code
// -------------------------

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
(function () {
    /** @var ContainerInterface $container */
    $container = require 'config/container.php';

    /** @var Application $app */
    $app     = $container->get(Application::class);
    $factory = $container->get(MiddlewareFactory::class);

    // Execute programmatic/declarative middleware pipeline and routing
    // configuration statements
    (require 'config/pipeline.php')($app, $factory, $container);
    (require 'config/routes.php')($app, $factory, $container);

    $app->run();
})();
