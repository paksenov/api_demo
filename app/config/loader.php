<?php

/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();

$loader->registerDirs(
    array(
        $config->application->modelsDir,
        $config->application->controllersDir,
        $config->application->libraryDir,
    )
)->register();

// composer
require_once __DIR__ . '/../vendor/autoload.php';
