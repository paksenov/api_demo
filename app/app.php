<?php

use Phalcon\Mvc\Micro\Collection as MicroCollection;

/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

/**
 * Add your routes here
 */
/*$app->get('/', function () use ($app) {
    echo $app['view']->render('index');
});*/

/**
 * Not found handler
 */
$app->notFound(function () use ($app) {
    $app->response->code404(true);
});

$app->after(function () use ($app) {
    $app->response->send();
});

/**
 * Users
 */
$users = new MicroCollection();
$users->setHandler(new UsersController());
$users->setPrefix('/Users');
$users->get('/', 'index');
$users->get('/{id}', 'get');
$users->get('/clients', 'clients');
$users->get('/executors', 'executors');
$users->get('/search', 'search');
$users->post('/', 'post');
$users->put('/', 'put');
$app->mount($users);


/**
 * Tasks
 */
$tasks = new MicroCollection();
$tasks->setHandler(new TasksController());
$tasks->setPrefix('/Tasks');
$tasks->get('/', 'index');
$tasks->get('/{id}', 'get');
$tasks->get('/user/{user_id}', 'user');
$tasks->post('/', 'post');
$tasks->delete('/', 'delete');
$app->mount($tasks);

/**
 * AssignTasks
 */
$assigntasks = new MicroCollection();
$assigntasks->setHandler(new AssignedTasksController());
$assigntasks->setPrefix('/AssignTasks');
$assigntasks->get('/', 'index');
$assigntasks->get('/{id}', 'get');
$assigntasks->get('/user/{user_id}', 'user');
$assigntasks->post('/', 'post');
$assigntasks->delete('/', 'delete');
$app->mount($assigntasks);