<?php

return [
    'get' => [
        '[/]' => [
            'controller' => 'App\Http\Web\Controller\IndexController',
            'action'     => 'index'
        ],
        '/orders[/]' => [
            'controller' => 'App\Http\Web\Controller\IndexController',
            'action'     => 'orders'
        ],
        '/users[/]' => [
            'controller' => 'App\Http\Web\Controller\UsersController',
            'action'     => 'index'
        ],
        '/logout[/]' => [
            'controller' => 'App\Http\Web\Controller\IndexController',
            'action'     => 'logout'
        ]
    ],
    'get,post' => [
        '/login[/]' => [
            'controller' => 'App\Http\Web\Controller\IndexController',
            'action'     => 'login'
        ],
        '/users/create[/]' => [
            'controller' => 'App\Http\Web\Controller\UsersController',
            'action'     => 'create'
        ],
        '/users/:id' => [
            'controller' => 'App\Http\Web\Controller\UsersController',
            'action'     => 'update'
        ]
    ],
    'post' => [
        '/users/delete[/]' => [
            'controller' => 'App\Http\Web\Controller\UsersController',
            'action'     => 'delete'
        ]
    ]
];