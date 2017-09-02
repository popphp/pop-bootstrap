<?php
/**
 * Pop Bootstrap Application
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace App\Console\Controller;

use Pop\Application;
use Pop\Console\Console;

/**
 * Console controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.0.0
 */
class ConsoleController extends \Pop\Controller\AbstractController
{

    /**
     * Application object
     * @var Application
     */
    protected $application = null;

    /**
     * Console object
     * @var \Pop\Console\Console
     */
    protected $console = null;

    /**
     * Constructor for the controller
     *
     * @param  Application $application
     * @param  Console     $console
     */
    public function __construct(Application $application, Console $console)
    {
        $this->application = $application;
        $this->console     = $console;
    }

    /**
     * Get application object
     *
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * Get request object
     *
     * @return Console
     */
    public function console()
    {
        return $this->console;
    }

    /**
     * Help command
     *
     * @return void
     */
    public function help()
    {
        $command = $this->console->colorize("./app", Console::BOLD_CYAN) . ' ' .
            $this->console->colorize("help", Console::BOLD_YELLOW) ;
        $this->console->append($command . "\t\t\t Show the help screen");

        $this->console->send();
    }

    /**
     * Default error action method
     *
     * @throws \App\Exception
     * @return void
     */
    public function error()
    {
        throw new \App\Exception('Invalid Command');
    }

}