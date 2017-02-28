<?php
/**
 * Pop Web Bootstrap Application Framework CLI PHP Script
 */

// Require autoloader
$autoloader = include __DIR__ . '/../vendor/autoload.php';

// Create main app object, register the app module and run the app
try {
    $app = new Pop\Application($autoloader, include __DIR__ . '/../app/config/app.cli.php');
    $app->register(new App\Module());
    $app->run();
} catch (\Exception $exception) {
    $app = new App\Module();
    $app->cliError($exception);
}
