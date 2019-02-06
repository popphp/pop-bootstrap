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
use Pop\Console\Command;

/**
 * Console controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
 */
abstract class AbstractController extends \Pop\Controller\AbstractController
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

        $this->console->setHelpColors(Console::BOLD_CYAN, Console::BOLD_YELLOW, Console::BOLD_GREEN);
        $this->console->addCommands([
            new Command('./app users', null, "List users"),
            new Command('./app users add', null, "Add a user"),
            new Command('./app users username', '<user>', "Change a user's username"),
            new Command('./app users password', '<user>', "Change a user's password"),
            new Command('./app users -a', '<user>', "Activate a user"),
            new Command('./app users -d', '<user>', "Deactivate a user"),
            new Command('./app users clear', '<user>', "Clear a user's failed login attempts"),
            new Command('./app users revoke', '<user>', "Revoke a user's auth tokens"),
            new Command('./app users remove', '<user>', "Remove a user"),
            new Command('./app help', null, "Show the help screen")
        ]);
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