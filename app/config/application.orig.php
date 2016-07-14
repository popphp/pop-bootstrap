<?php
/**
 * Pop Web Bootstrap Application Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * Pop Web Bootstrap Application Framework Configuration File
 */
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
                'tree' => include 'nav/main.php',
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
        ],
        'nav.side' => [
            'call'   => 'Pop\Nav\Nav',
            'params' => [
                'tree' => include 'nav/side.php',
                'config' => [
                    'top'     => [
                        'id'    => 'pop-side-nav',
                        'node'  => 'ul',
                        'class' => 'nav nav-sidebar'
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
    'pagination'        => 25,
    'multiple_sessions' => true,
    'login_attempts'    => 0,
    'session_timeout'   => 0,  // In minutes
    'timeout_warning'   => 0   // In seconds
];
