<?php
/**
 * Pop Web Bootstrap Application Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2017 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * Pop Web Bootstrap Application Framework Configuration File
 */
return [
    'routes'    => include 'routes/web.php',
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
        'mailer'  => [
            'call' => function() {
                return new \Pop\Mail\Mailer(new \Pop\Mail\Transport\Sendmail());
            }
        ],
        'nav.top' => [
            'call'   => 'Pop\Nav\Nav',
            'params' => [
                'tree' => include 'nav/top.php',
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
        'nav.fluid' => [
            'call'   => 'Pop\Nav\Nav',
            'params' => [
                'tree' => include 'nav/fluid.php',
                'config' => [
                    'top'     => [
                        'id'    => 'pop-fluid-nav',
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
        ],
        'nav.static' => [
            'call'   => 'Pop\Nav\Nav',
            'params' => [
                'tree' => include 'nav/static.php',
                'config' => [
                    'top'     => [
                        'id'    => 'pop-static-nav',
                        'node'  => 'ul'
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
    'pagination'        => 25,
    'multiple_sessions' => true,
    'login_attempts'    => 0,
    'session_timeout'   => 0,  // In minutes
    'timeout_warning'   => 0   // In seconds
];
