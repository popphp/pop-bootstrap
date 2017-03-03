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
 * @namespace
 */
namespace App\Controller\Console;

use Pop\Application;
use Pop\Console\Console;
use Pop\Service\Locator;

/**
 * Console Controller class
 *
 * @category   Pop\Bootstrap
 * @package    Pop\Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2017 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
class ConsoleController extends \Pop\Controller\AbstractController
{

    /**
     * Application object
     * @var Application
     */
    protected $application = null;

    /**
     * Services locator
     * @var Locator
     */
    protected $services = null;

    /**
     * Console object
     * @var \Pop\Console\Console
     */
    protected $console = null;

    /**
     * Config object
     * @var \ArrayObject
     */
    protected $config = null;

    /**
     * Constructor for the controller
     *
     * @param  Application $application
     * @param  Console     $console
     */
    public function __construct(Application $application, Console $console)
    {
        $this->application = $application;
        $this->services    = $application->services();
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
     * Get services object
     *
     * @return Locator
     */
    public function services()
    {
        return $this->services;
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
     * Get config object
     *
     * @return \ArrayObject
     */
    public function config()
    {
        return $this->config;
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
