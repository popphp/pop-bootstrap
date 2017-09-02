<?php

return [
    'get' => [
        '[/]' => [
            'controller' => 'App\Web\Controller\IndexController',
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
        ]
    ]
];