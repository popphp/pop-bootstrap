<?php

return [
    'get' => [
        '[/]' => [
            'controller' => 'Pab\Web\Controller\IndexController',
            'action'     => 'index'
        ],
        '/logout[/]' => [
            'controller' => 'Pab\Web\Controller\IndexController',
            'action'     => 'logout'
        ]
    ],
    'get,post' => [
        '/login[/]' => [
            'controller' => 'Pab\Web\Controller\IndexController',
            'action'     => 'login'
        ],
        '/forgot[/]' => [
            'controller' => 'Pab\Web\Controller\IndexController',
            'action'     => 'forgot'
        ]
    ]
];