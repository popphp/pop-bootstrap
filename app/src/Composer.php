<?php

namespace App;

use Pop\Console\Console;
use Pop\Db\Db;
use Pop\Db\Record;
use Pop\File\Dir;

class Composer
{

    public static function install($event)
    {
        $console   = new Console(100, '    ');

        if (!file_exists(__DIR__ . '/../../data')) {
            mkdir(__DIR__ . '/../../data');
        }

        chmod(__DIR__ . '/../../data', 0777);

        if (!file_exists(__DIR__ . '/../../app/config/application.php')) {
            $console->write();
            $console->write($console->colorize(
                'A configuration file was not detected. You will be prompted to create one.', Console::BOLD_YELLOW
            ));

            $console->write();

            // Configure application database
            $dbName      = '';
            $dbUser      = '';
            $dbPass      = '';
            $dbHost      = '';
            $dbPrefix    = '';

            $dbAdapters = self::getDbAdapters();
            $adapters   = array_keys($dbAdapters);
            $dbChoices  = [];
            $dsn        = null;
            $i          = 1;
            foreach ($dbAdapters as $a) {
                $console->write($i . ': ' . $a);
                $dbChoices[] = $i;
                $i++;
            }

            $console->write();
            $adapter = $console->prompt('Please select one of the above database adapters: ', $dbChoices);
            $console->write();

            // If PDO
            if (strpos($adapters[$adapter - 1], 'pdo') !== false) {
                $console->write('1: mysql');
                $console->write('2: pgsql');
                $console->write('3: sqlite');
                $console->write();
                $dsn         = $console->prompt('Please select the PDO DSN: ', [1, 2, 3]);
                $dbInterface = 'Pdo';
                $dbType      = str_replace('pdo_', '', strtolower($adapters[$adapter - 1]));
                $console->write();
            } else {
                $dbInterface = ucfirst(strtolower($adapters[$adapter - 1]));
                $dbType      = null;
            }

            // If SQLite
            if (($dsn == 3) || ($adapters[$adapter - 1] == 'sqlite')) {
                if (!file_exists(__DIR__ . '/../../data/.htpop.sqlite')) {
                    touch(__DIR__ . '/../../data/.htpop.sqlite');
                    chmod(__DIR__ . '/../../data/.htpop.sqlite', 0777);
                }
                $dbName     = __DIR__ . '/../../data/.htpop.sqlite';
                $realDbName = "__DIR__ . '/../../data/.htpop.sqlite'";
                $dbPrefix   = $console->prompt('DB Table Prefix: [pop_] ');
                $console->write();
            } else {
                $dbCheck = 1;
                while (null !== $dbCheck) {
                    $dbName   = $console->prompt('DB Name: ');
                    $dbUser   = $console->prompt('DB User: ');
                    $dbPass   = $console->prompt('DB Password: ');
                    $dbHost   = $console->prompt('DB Host: [localhost] ');
                    $dbPrefix = $console->prompt('DB Table Prefix: [pop_] ');

                    if ($dbHost == '') {
                        $dbHost = 'localhost';
                    }

                    $dbCheck = Db::check([
                        'database' => $dbName,
                        'username' => $dbUser,
                        'password' => $dbPass,
                        'host'     => $dbHost,
                        'type'     => $dbType,
                    ], $dbInterface);

                    if (null !== $dbCheck) {
                        $console->write();
                        $console->write($console->colorize(
                            'Database configuration test failed. Please try again.', Console::BOLD_RED
                        ));
                    } else {
                        $realDbName = "'" . $dbName . "'";

                        $console->write();
                        $console->write($console->colorize(
                            'Database configuration test passed.', Console::BOLD_GREEN
                        ));
                    }
                    $console->write();
                }
            }

            // Install database
            $sql = (stripos($dbInterface, 'pdo') !== false) ?
                __DIR__ . '/../data/phire.' . strtolower($dbType) . '.sql' :
                __DIR__ . '/../data/phire.' . strtolower($dbInterface) . '.sql';

            if ($dbPrefix == '') {
                $dbPrefix = 'pop_';
            }

            Db::install($sql, [
                'database' => $dbName,
                'username' => $dbUser,
                'password' => $dbPass,
                'host'     => $dbHost,
                'prefix'   => $dbPrefix,
                'type'     => $dbType
            ], $dbInterface);

            // Write config file
            $config = str_replace(
                [
                    "define('DB_PREFIX', '');",
                    "'adapter'  => '',",
                    "'database' => '',",
                    "'username' => '',",
                    "'password' => '',",
                    "'host'     => '',",
                    "'type'     => null"
                ],
                [
                    "define('DB_PREFIX', '" . $dbPrefix . "');",
                    "'adapter'  => '" . strtolower($dbInterface) . "',",
                    "'database' => ' . $realDbName . ',",
                    "'username' => '" . $dbUser . "',",
                    "'password' => '" . $dbPass . "',",
                    "'host'     => '" . $dbHost . "',",
                    "'type'     => '" . strtolower($dbInterface) . "'"
                ], file_get_contents(__DIR__ . '/../../app/config/application.orig.php')
            );

            file_put_contents(__DIR__ . '/../../app/config/application.php', $config);

            $console->write($console->colorize('Application configuration completed.', Console::BOLD_GREEN));
        }

        $console->write();
        $console->write('Thank you for using Pop!');
        $console->write();
    }

    /**
     * Get the DB adapters
     *
     * @return array
     */
    public static function getDbAdapters()
    {
        $dbAdapters = [];
        $pdoDrivers = (class_exists('Pdo', false)) ? \PDO::getAvailableDrivers() : [];

        if (class_exists('mysqli', false)) {
            $dbAdapters['mysql'] = 'Mysql';
        }
        if (function_exists('pg_connect')) {
            $dbAdapters['pgsql'] = 'PostgreSQL';
        }
        if (class_exists('Sqlite3', false)) {
            $dbAdapters['sqlite'] = 'SQLite';
        }
        if (in_array('mysql', $pdoDrivers)) {
            $dbAdapters['pdo_mysql'] = 'PDO\Mysql';
        }
        if (in_array('pgsql', $pdoDrivers)) {
            $dbAdapters['pdo_pgsql'] = 'PDO\PostgreSQL';
        }
        if (in_array('sqlite', $pdoDrivers)) {
            $dbAdapters['pdo_sqlite'] = 'PDO\SQLite';
        }

        return $dbAdapters;
    }

}