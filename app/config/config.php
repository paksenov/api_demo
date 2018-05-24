<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

return new \Phalcon\Config(array(

    'database' => array(
        'adapter'    => 'Postgresql',
        'host'       => 'localhost',
        'username'   => 'postgres',
        'password'   => '',
        'dbname'     => 'api_demo',
        //'charset'    => 'utf8',
    ),

    'database_prod' => array(
        'adapter'    => 'Postgresql',
        'host'       => 'localhost',
        'username'   => 'api_demo',
        'password'   => 'api_demo',
        'dbname'     => 'api_demo',
        //'charset'    => 'utf8',
    ),

    'application' => array(
        'modelsDir'         => __DIR__ . '/../models/',
        'controllersDir'    => __DIR__ . '/../controllers/',
        'libraryDir'        => __DIR__ . '/../library/',
        'baseUri'           => '/',
        'cryptSalt'         => 'fUYFGGUyokodw-[N67g\V%G#.{Gd*cHQ',
        'tmp'               => __DIR__ . '/../tmp/',

    ),

    'beanstalk' => [
        'host'                  => '127.0.0.1',
        'post'                  => '11300',
        'prefix'                => 'api',
    ],

    'mailgun' => [
        'key'       => 'key-',
        'domain'    => 'domain.ltd',
        'from'      => 'Domain <noreply@domain.ltd>',
    ],
));
