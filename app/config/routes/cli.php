<?php
/**
 * CLI Routes
 */
return [
    '<controller> [<action>] [<param>]' => [
        'prefix' => 'App\\Controller\\Console\\'
    ],
    '*' => [
        'controller' => 'App\Controller\Console\ConsoleController',
        'action'     => 'error'
    ]
];