<?php

return [
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
        ]
    ]
];