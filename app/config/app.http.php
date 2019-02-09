<?php
/**
 * Pop Boostrap HTTP Configuration File
 */

return [
    'routes'         => include 'routes/http.php',
    'database'       => include 'database.php',
    'forms'          => include 'forms.php',
    'auth_attempts'  => 3,
    'token_expires'  => 1800,
    'maintenance'    => false,
    'services'       => [
        'session'    => 'Pop\Session\Session::getInstance',
        'cookie'     => [
            'call'   => 'Pop\Cookie\Cookie::getInstance',
            'params' => ['options' => ['path' => '/']]
        ]
    ]
];

