<?php
/**
 * Pop Bootstrap Application
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace App\Console\Event;

/**
 * Console event class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.2.0
 */
class Console
{

    /**
     * Display console header
     *
     * @return void
     */
    public static function header()
    {
        echo PHP_EOL . '    Pop Bootstrap Console' . PHP_EOL;
        echo '    =====================' . PHP_EOL . PHP_EOL;
    }

    /**
     * Display console footer
     *
     * @return void
     */
    public static function footer()
    {
        echo PHP_EOL;
    }

}