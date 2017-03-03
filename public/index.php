<?php
/**
 * Pop Web Bootstrap Application Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2017 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

// Require autoloader
$autoloader = include __DIR__ . '/../vendor/autoload.php';

// Create main app object, register the app module and run the app
try {
    $app = new Pop\Application($autoloader, include __DIR__ . '/../app/config/app.web.php');
    $app->register(new App\Module());
    $app->run();
} catch (\Exception $exception) {
    $app = new App\Module();
    $app->webError($exception);
}
