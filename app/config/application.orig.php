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
                'adapter' => '',       // Database adapter ('mysql', 'pgsql', 'sqlite', 'pdo')
                'options' => [
                    'database' => '',  // Database name
                    'username' => '',  // Database username
                    'password' => '',  // Database password
                    /*
                    'host'     => ''   // Database host, defaults to 'localhost'
                    'type'     => ''   // PDO-only DSN type ('mysql', 'pgsql', 'sqlite')
                    */
                ]
            ]
        ]
    ],
    'forms' => include 'forms.php',
];
