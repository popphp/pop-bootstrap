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
                'adapter' => '',       // Database adapter  ('mysql', 'pgsql', 'sqlite', 'pdo')
                'options' => [
                    'database' => '',  // Database name     (for sqlite, it's the location of the SQLite database file)
                    'username' => '',  // Database username (not required for SQLite)
                    'password' => '',  // Database password (not required for SQLite)
                    /*
                    'host'     => ''   // Database host     (defaults to 'localhost')
                    'type'     => ''   // PDO-only DSN type ('mysql', 'pgsql', 'sqlite')
                    */
                ]
            ]
        ]
    ],
    'forms' => include 'forms.php',
];
