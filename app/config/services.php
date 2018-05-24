<?php
/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */

use Phalcon\Di\FactoryDefault;

$di = new FactoryDefault();

$di->setShared('config', function() use ($config) {
    return $config;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($config) {

    if ($_SERVER["SERVER_NAME"] == 'localhost') {
        $dbConfig = $config->database->toArray();
    } else {
        $dbConfig = $config->database_prod->toArray();
    }

    $adapter = $dbConfig['adapter'];
    unset($dbConfig['adapter']);

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

    return new $class($dbConfig);
});

/**
 * Sphinx connection
 */
/*$di->setShared('sphinx', function () use ($config) {
    return new Phalcon\Db\Adapter\Pdo\Mysql(array(
        'host' => $config->sphinx->host,
        'port' => $config->sphinx->port,
        'charset' => 'utf8',
    ));
});*/

/**
 * Beanstalk service
 */
/*$di->set('beanstalk', function() use ($config) {
    return new Phalcon\Queue\Beanstalk\Extended($config->beanstalk->toArray());
});*/

$di->setShared('response',  function() {
    $response =  new Response();
    $response->setContentType('application/json');
    return $response;
});

/**
 * Hashids service
 */
$di->setShared('hashids', function() use ($config) {
    return new \Hashids\Hashids($config->application->cryptSalt, 8);
});

/**
 * Crypt service
 */
$di->setShared('crypt', function () use ($config) {
    $crypt = new \Phalcon\Crypt();
    $crypt->setKey($config->application->cryptSalt);
    return $crypt;
});

$di->setShared('security', function() {
    $security = new \Phalcon\Security();
    $security->setWorkFactor(12);
    return $security;
});

/**
 * Mailgun
 */
/*$di->set('mailgun',function() use ($config) {
    return new Mailgun\Mailgun($config->mailgun->key);
});*/