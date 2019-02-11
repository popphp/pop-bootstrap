<?php

return [
    'users' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'index',
        'help'       => 'List users'
    ],
    'users add' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'add',
        'help'       => 'Add a user'
    ],
    'users username <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'username',
        'help'       => "Change a user's username"
    ],
    'users password <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'password',
        'help'       => "Change a user's password"
    ],
    'users -a <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'activate',
        'help'       => 'Activate a user'
    ],
    'users -d <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'deactivate',
        'help'       => 'Deactivate a user'
    ],
    'users clear <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'clear',
        'help'       => "Clear a user's failed login attempts"
    ],
    'users revoke <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'revoke',
        'help'       => "Revoke a user's auth tokens"
    ],
    'users remove <user>' => [
        'controller' => 'App\Console\Controller\UsersController',
        'action'     => 'remove',
        'help'       => 'Remove a user' . PHP_EOL
    ],
    'version' => [
        'controller' => 'App\Console\Controller\ConsoleController',
        'action'     => 'version',
        'help'       => 'Show the version'
    ],
    'help' => [
        'controller' => 'App\Console\Controller\ConsoleController',
        'action'     => 'help',
        'help'       => 'Show the help screen'
    ],
    '*' => [
        'controller' => 'App\Console\Controller\ConsoleController',
        'action'     => 'error'
    ]
];