<?php
/**
 * PABs API HTTP Configuration File
 */

return [
    'routes'         => include 'routes/http.php',
    'database'       => include 'database/mysql.php',
    'forms'          => include 'forms.php',
    'auth_attempts'  => 3,
    'token_expires'  => 1800,
    'maintenance'    => false,
    'pagination'     => 25,
    'services'       => [
        'acl'        => 'Pop\Acl\Acl',
        'session'    => 'Pop\Session\Session::getInstance',
        'cookie'     => [
            'call'   => 'Pop\Cookie\Cookie::getInstance',
            'params' => ['options' => ['path' => '/']]
        ]
    ]
];

