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
namespace App\Console\Controller;

use Pop\Console\Console;

/**
 * Console controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.2.0
 */
class ConsoleController extends AbstractController
{

    /**
     * Version command
     *
     * @return void
     */
    public function version()
    {
        $this->console->write('Version: ' . $this->console->colorize(\App\Module::VERSION, Console::BOLD_GREEN));
    }

    /**
     * Help command
     *
     * @return void
     */
    public function help()
    {
        $this->console->help();
    }

}