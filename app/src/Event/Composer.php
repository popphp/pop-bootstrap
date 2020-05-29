<?php
/**
 * Pop Bootstrap Application (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace App\Event;

use Pop\Console\Console;
use Pop\Dir\Dir;
use Pop\Kettle\Model;

/**
 * Composer event class
 *
 * @category   Pop\Bootstrap
 * @package    Pop\Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
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

        $console     = new Console(100, '    ');
        $dbModel     = new Model\Database();
        $location    = getcwd();
        $hasDbConfig = false;

        if (file_exists($location . '/app/config/database.php')) {
            $database    = include $location . '/app/config/database.php';
            $hasDbConfig = (!empty($database['default']['adapter']));
        } else if (!file_exists($location . '/app/config/database.php')) {
            $hasDbConfig = false;
            copy($location . '/app/config/database.orig.php', $location . '/app/config/database.php');
        }

        if (!$hasDbConfig) {
            $console->write();
            $createDb = $console->prompt(
                'Would you like configure the database? [Y/N] ', ['y', 'n']
            );

            if (strtolower($createDb) == 'y') {
                $dbModel->configure($console, $location);

                $dir   = new Dir($location . '/database/seeds/default', ['filesOnly' => true]);
                $seeds = $dir->getFiles();

                sort($seeds);
                $console->write('Seeding database...');
                $dbConfig = include $location . '/app/config/database.php';
                foreach ($seeds as $seed) {
                    if (stripos($seed, '.sql') !== false) {
                        $dbModel->install(
                            $dbConfig['default'],
                            $location . '/database/seeds/default/' . $seed
                        );
                    }
                }
            }
        } else {
            $console->write($console->colorize('Application already configured. Nothing to do.', Console::BOLD_GREEN));
        }

        $console->write();
        $console->write('Done!');
        $console->write();
    }

}