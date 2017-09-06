<?php
/**
 * Pop Bootstrap Application (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2017 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace App\Event;

use Pop\Console\Console;
use Pop\Db\Db;

/**
 * Composer event class
 *
 * @category   Pop\Bootstrap
 * @package    Pop\Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2017 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.0.0
 */
class Composer
{

    /**
     * Composer install method
     *
     * @param  mixed $event
     * @return void
     */
    public static function install($event)
    {
        $console = new Console(100, '    ');

        chmod(__DIR__ . '/../../../data', 0777);

        if (!file_exists(__DIR__ . '/../../../app/config/database/mysql.php')) {
            $console->write();
            $console->write($console->colorize(
                'A configuration file was not detected.', Console::BOLD_YELLOW
            ));
            $console->write();
            $createConfig = $console->prompt(
                'Would you like to create one and install the database? [Y/N] ', ['y', 'n']
            );

            if (strtolower($createConfig) == 'y') {
                $console->write();

                // Configure application database
                $dbName     = '';
                $dbUser     = '';
                $dbPass     = '';
                $dbHost     = '';
                $realDbName = '';
                $dbAdapters = [];
                $pdoDrivers = (class_exists('Pdo', false)) ? \PDO::getAvailableDrivers() : [];

                if (class_exists('mysqli', false)) {
                    $dbAdapters['mysql'] = 'Mysql';
                }
                if (in_array('mysql', $pdoDrivers)) {
                    $dbAdapters['pdo_mysql'] = 'PDO Mysql';
                }

                $adapters  = array_keys($dbAdapters);
                $dbChoices = [];
                $i         = 1;

                foreach ($dbAdapters as $a) {
                    $console->write($i . ': ' . $a);
                    $dbChoices[] = $i;
                    $i++;
                }

                $console->write();
                $adapter = $console->prompt('Please select one of the above database adapters: ', $dbChoices);
                $console->write();

                // If PDO
                if (stripos($adapters[$adapter - 1], 'pdo') !== false) {
                    $dbInterface = 'Pdo';
                    $dbType      = 'mysql';
                } else {
                    $dbInterface = ucfirst(strtolower($adapters[$adapter - 1]));
                    $dbType      = null;
                }

                $dbCheck = 1;
                while (null !== $dbCheck) {
                    $dbName     = $console->prompt('DB Name: ');
                    $dbUser     = $console->prompt('DB User: ');
                    $dbPass     = $console->prompt('DB Password: ');
                    $dbHost     = $console->prompt('DB Host: [localhost] ');
                    $realDbName = "'" . $dbName . "'";

                    if ($dbHost == '') {
                        $dbHost = 'localhost';
                    }

                    $dbCheck = Db::check($dbInterface, [
                        'database' => $dbName,
                        'username' => $dbUser,
                        'password' => $dbPass,
                        'host'     => $dbHost,
                        'type'     => $dbType,
                    ]);

                    if (null !== $dbCheck) {
                        $console->write();
                        $console->write($console->colorize(
                            'Database configuration test failed. Please try again.', Console::BOLD_RED
                        ));
                    } else {
                        $console->write();
                        $console->write($console->colorize(
                            'Database configuration test passed.', Console::BOLD_GREEN
                        ));
                    }
                    $console->write();
                }

                Db::install(__DIR__ . '/../../database/app.mysql.sql', $dbInterface, [
                    'database' => $dbName,
                    'username' => $dbUser,
                    'password' => $dbPass,
                    'host'     => $dbHost,
                    'type'     => $dbType
                ]);

                // Write web config file
                $dbConfig = str_replace(
                    [
                        "'adapter'  => '',",
                        "'database' => '',",
                        "'username' => '',",
                        "'password' => '',",
                        "'host'     => '',",
                        "'type'     => ''"
                    ],
                    [
                        "'adapter'  => '" . strtolower($dbInterface) . "',",
                        "'database' => " . $realDbName . ",",
                        "'username' => '" . $dbUser . "',",
                        "'password' => '" . $dbPass . "',",
                        "'host'     => '" . $dbHost . "',",
                        "'type'     => '" . $dbType . "'"
                    ], file_get_contents(__DIR__ . '/../../../app/config/database/mysql.orig.php')
                );

                file_put_contents(__DIR__ . '/../../../app/config/database/mysql.php', $dbConfig);

                $console->write($console->colorize('Application configuration completed.', Console::BOLD_GREEN));
                $console->write();
            }
        } else {
            $console->write($console->colorize('Application already configured. Nothing to do.', Console::BOLD_GREEN));
            $console->write();
        }
    }

}