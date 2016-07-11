<?php

return [
    'routes'    => include 'routes.php',
    'resources' => include 'resources.php',
    'forms'     => include 'forms.php',
    'services'  => [
        'session'    => 'Pop\Session\Session::getInstance',
        'acl'        => 'Pop\Acl\Acl',
        'database'   => [
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
    'application_title'          => 'My Application',
    'multiple_sessions'          => true,
    'multiple_session_warning'   => false,
    'session_expiration'         => 1800,
    'session_expiration_warning' => true
];
