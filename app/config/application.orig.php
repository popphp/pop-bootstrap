<?php

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
        'type'     => null
    ],
    'services'  => [
        'session' => 'Pop\Session\Session::getInstance',
        'acl'     => 'Pop\Acl\Acl'
    ],
    'application_title' => 'My Application'
];
