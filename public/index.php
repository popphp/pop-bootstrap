<?php
/**
 * Pop Bootstrap Web Application
 */

$autoloader = include __DIR__ . '/../vendor/autoload.php';

try {
    $app = new Popcorn\Pop($autoloader, include __DIR__ . '/../app/config/app.http.php');
    $app->register(new App\Module());
    $app->run();
} catch (\Exception $exception) {
    $app = new App\Module();
    $app->error($exception);
}