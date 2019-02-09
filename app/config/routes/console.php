<?php

return [
    'version' => [
        'controller' => 'App\Console\Controller\ConsoleController',
        'action'     => 'version'
    ],
    'help' => [
        'controller' => 'App\Console\Controller\ConsoleController',
        'action'     => 'help'
    ],
    'users' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'index'
    ],
    'users add' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'add'
    ],
    'users -a <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'activate'
    ],
    'users -d <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'deactivate'
    ],
    'users username <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'username'
    ],
    'users password <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'password'
    ],
    'users clear <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'clear'
    ],
    'users revoke <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'revoke'
    ],
    'users remove <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'remove'
    ],
    '*' => [
        'controller' => 'App\Console\Controller\ConsoleController',
        'action'     => 'error'
    ]
];