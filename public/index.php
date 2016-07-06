<?php

$autoloader = include __DIR__ . '/../vendor/autoload.php';
$config     = include __DIR__ . '/../app/config/application.php';

try {
    $app = new Pop\Application($autoloader, $config);
    $app->router()->addControllerParams('*', [
        'application' => $app,
        'request'     => new \Pop\Http\Request(),
        'response'    => new \Pop\Http\Response()
    ]);

    $app->run();
} catch (\Exception $exception) {
    echo $exception->getMessage();
}
