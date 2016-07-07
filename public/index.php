<?php

$autoloader = include __DIR__ . '/../vendor/autoload.php';

try {
    $app = new Pop\Application($autoloader);
    $app->register('app', new App\Module(include __DIR__ . '/../app/config/application.php'));
    $app->run();
} catch (\Exception $exception) {
    echo $exception->getMessage();
}
