<?php

return [
    'help' => [
        'controller' => 'Pab\Console\Controller\ConsoleController',
        'action'     => 'help'
    ],
    '*' => [
        'controller' => 'Pab\Console\Controller\ConsoleController',
        'action'     => 'error'
    ]
];