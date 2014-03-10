<?php

/**
 * Initiate the class autoloaders
 */
$autoloader = $paths['core'] . '/aptitude/Autoloader.php';
if (file_exists($autoloader)) require $autoloader;

$composer = $paths['base'] . '/vendor/autoload.php';
if (file_exists($composer)) require $composer;

// Instantiate the autoloader class
$autoloader = new Autoloader;

// Register the autoloader class with the spl_autoload_register function
$autoloader->register();

// Add autload paths
$autoloader->addDirectories(array(
	$paths['core'],
	$paths['app'] . '/controllers',
	$paths['app'] . '/models',
	$paths['app'] . '/services',
));