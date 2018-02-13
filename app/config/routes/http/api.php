<?php

return [
    'options,get' => [
        '/api/users[/:id]' => [
            'controller' => 'App\Http\Api\Controller\UsersController',
            'action'     => 'index'
        ]
    ],
    'options,put' => [
        '/api/users/:id' => [
            'controller' => 'App\Http\Api\Controller\UsersController',
            'action'     => 'update'
        ]
    ],
    'options,post' => [
        '/api/auth[/]' => [
            'controller' => 'App\Http\Api\Controller\AuthController',
            'action'     => 'auth'
        ],
        '/api/auth/token[/]' => [
            'controller' => 'App\Http\Api\Controller\TokenController',
            'action'     => 'token'
        ],
        '/api/auth/token/refresh[/]' => [
            'controller' => 'App\Http\Api\Controller\TokenController',
            'action'     => 'refresh'
        ],
        '/api/auth/token/revoke[/]' => [
            'controller' => 'App\Http\Api\Controller\TokenController',
            'action'     => 'revoke'
        ],
        '/api/users[/]' => [
            'controller' => 'App\Http\Api\Controller\UsersController',
            'action'     => 'create'
        ]
    ],
    'options,delete' => [
        '/api/users[/:id]' => [
            'controller' => 'App\Http\Api\Controller\UsersController',
            'action'     => 'delete'
        ]
    ],
];