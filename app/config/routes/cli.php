<?php

return [
    'help' => [
        'controller' => 'App\Console\Controller\ConsoleController',
        'action'     => 'help'
    ],
    '*' => [
        'controller' => 'App\Console\Controller\ConsoleController',
        'action'     => 'error'
    ]
];