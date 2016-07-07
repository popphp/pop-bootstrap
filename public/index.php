<?php

$autoloader = include __DIR__ . '/../vendor/autoload.php';

try {
    $app = new Pop\Application($autoloader, include __DIR__ . '/../app/config/application.php');
    $app->register('app', new App\Module($app));
    $app->run();
} catch (\Exception $exception) {
    $app = new App\Module();
    $app->error($exception);
}
