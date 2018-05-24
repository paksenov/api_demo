<?php

use Phalcon\Mvc\Micro;

error_reporting(E_ALL);
date_default_timezone_set("Europe/Moscow");

try {

    /**
     * Read the configuration
     */
    $config = include __DIR__ . "/../app/config/config.php";

    /**
     * Include Services
     */
    include __DIR__ . '/../app/config/services.php';

    /**
     * Include Autoloader
     */
    include __DIR__ . '/../app/config/loader.php';

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Micro($di);

    /**
     * Include Application
     */
    include __DIR__ . '/../app/app.php';

    /**
     * Handle the request
     */
    $app->handle();

} catch (\Exception $e) {
      echo $e->getMessage() . '<br>';
      echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
