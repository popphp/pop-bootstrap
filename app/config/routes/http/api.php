<?php

return [
    'options,get' => [
        '/api/version[/]' => [
            'controller' => 'Pab\Api\Controller\IndexController',
            'action'     => 'version'
        ]
    ],
    'options,post' => [
        '/api/auth[/]' => [
            'controller' => 'Pab\Api\Auth\Controller\AuthController',
            'action'     => 'auth'
        ],
        '/api/auth/token[/]' => [
            'controller' => 'Pab\Api\Auth\Controller\TokenController',
            'action'     => 'token'
        ],
        '/api/auth/token/refresh[/]' => [
            'controller' => 'Pab\Api\Auth\Controller\TokenController',
            'action'     => 'refresh'
        ],
        '/api/auth/token/revoke[/]' => [
            'controller' => 'Pab\Api\Auth\Controller\TokenController',
            'action'     => 'revoke'
        ]
    ]
];