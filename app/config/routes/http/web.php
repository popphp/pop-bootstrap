<?php

return [
    'get' => [
        '[/]' => [
            'controller' => 'App\Web\Controller\IndexController',
            'action'     => 'index'
        ],
        '/orders[/]' => [
            'controller' => 'App\Web\Controller\IndexController',
            'action'     => 'orders'
        ],
        '/users[/]' => [
            'controller' => 'App\Web\Controller\UsersController',
            'action'     => 'index'
        ],
        '/logout[/]' => [
            'controller' => 'App\Web\Controller\IndexController',
            'action'     => 'logout'
        ]
    ],
    'get,post' => [
        '/login[/]' => [
            'controller' => 'App\Web\Controller\IndexController',
            'action'     => 'login'
        ],
        '/users/add[/]' => [
            'controller' => 'App\Web\Controller\UsersController',
            'action'     => 'add'
        ],
        '/users/:id' => [
            'controller' => 'App\Web\Controller\UsersController',
            'action'     => 'edit'
        ]
    ],
    'post' => [
        '/users/remove[/]' => [
            'controller' => 'App\Web\Controller\UsersController',
            'action'     => 'remove'
        ]
    ]
];