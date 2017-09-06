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
            'controller' => 'App\Web\Users\Controller\IndexController',
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
            'controller' => 'App\Web\Users\Controller\IndexController',
            'action'     => 'add'
        ],
        '/users/:id' => [
            'controller' => 'App\Web\Users\Controller\IndexController',
            'action'     => 'edit'
        ]
    ],
    'post' => [
        '/users/remove[/]' => [
            'controller' => 'App\Web\Users\Controller\IndexController',
            'action'     => 'remove'
        ]
    ]
];