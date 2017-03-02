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
 * Pop Web Bootstrap Application Framework CLI Configuration File
 */

return [
    'routes'    => include 'routes/cli.php',
    'database'  => [
        'adapter'  => '',
        'database' => '',
        'username' => '',
        'password' => '',
        'host'     => '',
        'type'     => ''
    ],
    'services'  => [
        'mailer'  => [
            'call' => function() {
                return new \Pop\Mail\Mailer(new \Pop\Mail\Transport\Sendmail());
            }
        ]
    ],
    'application_title' => 'My Application'
];
