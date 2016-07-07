<?php

return [
    '/' => [
        'controller' => 'App\Controller\IndexController',
        'action'     => 'index'
    ],
    '/login' => [
        'controller' => 'App\Controller\IndexController',
        'action'     => 'login'
    ],
    '/logout' => [
        'controller' => 'App\Controller\IndexController',
        'action'     => 'logout'
    ],
    '/forgot' => [
        'controller' => 'App\Controller\IndexController',
        'action'     => 'forgot'
    ],
    '/profile' => [
        'controller' => 'App\Controller\IndexController',
        'action'     => 'profile'
    ],
    '/verify/:id/:hash' => [
        'controller' => 'App\Controller\IndexController',
        'action'     => 'verify'
    ],
    '*' => [
        'controller' => 'App\Controller\IndexController',
        'action'     => 'error'
    ]
];