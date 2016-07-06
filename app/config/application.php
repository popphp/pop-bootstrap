<?php

return [
    'routes' => [
        '/' => [
            'controller' => 'App\Controller\IndexController',
            'action'     => 'index'
        ],
        '*' => [
            'controller' => 'App\Controller\IndexController',
            'action'     => 'error'
        ]
    ],
    'services' => [
        'session'   => 'Pop\Web\Session::getInstance',
        'acl'       => 'Pop\Acl\Acl'
    ]
];
