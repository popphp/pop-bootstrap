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
        '/users/add[/]' => [
            'controller' => 'App\Http\Web\Controller\UsersController',
            'action'     => 'add'
        ],
        '/users/:id' => [
            'controller' => 'App\Http\Web\Controller\UsersController',
            'action'     => 'edit'
        ]
    ],
    'post' => [
        '/users/remove[/]' => [
            'controller' => 'App\Http\Web\Controller\UsersController',
            'action'     => 'remove'
        ]
    ]
];