<?php

return [
    'routes' => [
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
        '*' => [
            'controller' => 'App\Controller\IndexController',
            'action'     => 'error'
        ]
    ],
    'services' => [
        'session'   => 'Pop\Web\Session::getInstance',
        'acl'       => 'Pop\Acl\Acl',
        'database'  => [
            'call'   => 'Pop\Db\Db::connect',
            'params' => [
                'adapter' => 'mysql',
                'options' => [
                    'database' => 'popdb',
                    'username' => 'popuser',
                    'password' => '12pop34',
                    'host'     => 'localhost'
                ]
            ]
        ]
    ],
    'forms' => include 'forms.php',
];
