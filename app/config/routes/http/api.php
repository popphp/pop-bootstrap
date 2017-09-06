<?php

return [
    'options,get' => [
        '/api/users[/]' => [
            'controller' => 'App\Api\Users\Controller\UsersController',
            'action'     => 'index'
        ],
        '/api/users/:id' => [
            'controller' => 'App\Api\Users\Controller\UsersController',
            'action'     => 'index'
        ]
    ],
    'options,put' => [
        '/api/users[/]' => [
            'controller' => 'App\Api\Users\Controller\UsersController',
            'action'     => 'add'
        ]
    ],
    'options,post' => [
        '/api/auth[/]' => [
            'controller' => 'App\Api\Auth\Controller\AuthController',
            'action'     => 'auth'
        ],
        '/api/auth/token[/]' => [
            'controller' => 'App\Api\Auth\Controller\TokenController',
            'action'     => 'token'
        ],
        '/api/auth/token/refresh[/]' => [
            'controller' => 'App\Api\Auth\Controller\TokenController',
            'action'     => 'refresh'
        ],
        '/api/auth/token/revoke[/]' => [
            'controller' => 'App\Api\Auth\Controller\TokenController',
            'action'     => 'revoke'
        ],
        '/api/users/:id' => [
            'controller' => 'App\Api\Users\Controller\UsersController',
            'action'     => 'edit'
        ]
    ]
];