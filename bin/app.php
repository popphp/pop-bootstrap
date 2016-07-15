<?php
/**
 * Pop Web Bootstrap Application Framework CLI PHP Script
 */

// Require autoloader
$autoloader = include __DIR__ . '/../vendor/autoload.php';

// Create main app object, register the app module and run the app
try {
    $config = include __DIR__ . '/../app/config/application.php';
    $config['routes'] = [
        '<controller> [<action>] [<param>]' => [
            'prefix' => 'App\\Controller\\Console\\'
        ],
        '*' => [
            'controller' => 'App\Controller\Console\ConsoleController',
            'action'     => 'error'
        ]
    ];

    unset($config['services']);

    $app = new Pop\Application($autoloader, $config);
    $app->register('app', new App\Console($app));
    $app->run();
} catch (\Exception $exception) {
    $app = new App\Console();
    $app->error($exception);
}
