<?php

/**
 * Turn on PHP error reporting.
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * Define system paths.
 */
$paths = require 'paths.php';

/**
 * Set autoloaders.
 */
require $paths['core'] . '/autoloader.php';

/**
 * Load the app config.
 */
$config = require $paths['app'] . '/config/app.php';

/**
 * Create the request class.
 */
$request = new Aptitude\HTTP\Request;

/**
 * Create the application instance.
 */
$app = new Aptitude\Application($request);

/**
 * Set the app itself as a service in the container to allow other
 * services access to the application instance.
 */
$app['app'] = $app;

/**
 * Set the application in the facade class.
 */
Aptitude\Facade::setFacadeApplication($app);

/**
 * Save application paths.
 */
$app->setPaths($paths);

/**
 * Register the services set in the app config file.
 */
$app->registerServices($config['services']);

/**
 * Register aliases set in the app config.
 */
$app->registerAliases($config['aliases']);

/**
 * Load application routes.
 */
$router = require $paths['app'] . '/routes.php';

$app->setRouter($router);

return $app;