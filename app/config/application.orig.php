<?php

if (!defined('DB_PREFIX')) {
    define('DB_PREFIX', '');
}

return [
    'routes'    => include 'routes.php',
    'resources' => include 'resources.php',
    'forms'     => include 'forms.php',
    'database'  => [
        'adapter'  => '',
        'database' => '',
        'username' => '',
        'password' => '',
        'host'     => '',
        'type'     => ''
    ],
    'services'  => [
        'session' => 'Pop\Session\Session::getInstance',
        'acl'     => 'Pop\Acl\Acl',
        'nav.pop' => [
            'call'   => 'Pop\Nav\Nav',
            'params' => [
                'tree' => [
                    'users' => [
                        'name' => 'Users',
                        'href' => '/users',
                        'acl'  => [
                            'resource'   => 'users',
                            'permission' => 'index'
                        ],
                        'attributes' => [
                            'class' => 'users-nav-icon'
                        ]
                    ],
                    'roles' => [
                        'name' => 'Roles',
                        'href' => '/roles',
                        'acl'  => [
                            'resource'   => 'roles',
                            'permission' => 'index'
                        ],
                        'attributes' => [
                            'class' => 'roles-nav-icon'
                        ]
                    ]
                ],
                'config' => [
                    'top'     => [
                        'id'    => 'pop-nav',
                        'node'  => 'ul',
                        'class' => 'nav navbar-nav'
                    ],
                    'parent' => [
                        'node' => 'ul'
                    ],
                    'child'  => [
                        'node' => 'li'
                    ],
                    'indent' => '    '
                ]
            ]
        ]
    ],
    'application_title' => 'My Application',
    'force_ssl'         => false,
    'pagination'        => 25
];
