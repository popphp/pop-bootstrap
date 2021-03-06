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
namespace App;

use Pop\Application;
use Pop\Db\Db;
use Pop\Db\Record;
use Pop\Http\Server\Request;
use Pop\Http\Server\Response;
use Pop\View\View;

/**
 * Main module class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.5.0
 */
class Module extends \Pop\Module\Module
{

    /**
     * Application version
     * @var string
     */
    const VERSION = '4.5.0';

    /**
     * Module name
     * @var string
     */
    protected $name = 'pop-bootstrap';

    /**
     * Register module
     *
     * @param  Application $application
     * @throws \Pop\Db\Adapter\Exception
     * @throws \Pop\Http\Exception
     * @throws \Pop\Service\Exception
     * @throws \Pop\Module\Exception
     * @throws \ReflectionException
     * @return Module
     */
    public function register(Application $application)
    {
        parent::register($application);

        $this->initDb($this->application->config()['database']['default']);

        if ($this->application->router()->isCli()) {
            $this->registerCli();
        } else {
            $this->registerHttp();
        }

        return $this;
    }

    /**
     * Register HTTP
     *
     * @return void
     */
    public function registerHttp()
    {
        if (null !== $this->application->router()) {
            $this->application->router()->addControllerParams(
                '*', [
                    'application' => $this->application,
                    'request'     => new Request(),
                    'response'    => new Response()
                ]
            );
        }

        $this->application->on('app.dispatch.pre', 'App\Http\Api\Event\Options::check')
             ->on('app.dispatch.pre', 'App\Http\Api\Event\Auth::check')
             ->on('app.dispatch.pre', 'App\Http\Api\Event\Maintenance::check')
             ->on('app.dispatch.pre', 'App\Http\Web\Event\Session::check')
             ->on('app.dispatch.pre', 'App\Http\Web\Event\Maintenance::check');
    }

    /**
     * Register CLI
     *
     * @return void
     */
    public function registerCli()
    {
        if (null !== $this->application->router()) {
            $this->application->router()->addControllerParams(
                '*', [
                    'application' => $this->application,
                    'console'     => new \Pop\Console\Console(120, '    ')
                ]
            );
        }

        $this->application->on('app.route.pre', 'App\Console\Event\Console::header')
             ->on('app.dispatch.post', 'App\Console\Event\Console::footer');
    }

    /**
     * Determine if request is a CLI request
     *
     * @return boolean
     */
    public function isCli()
    {
        return $this->application->router()->isCli();
    }

    /**
     * Determine if request is an HTTP request
     *
     * @return boolean
     */
    public function isHttp()
    {
        return $this->application->router()->isHttp();
    }

    /**
     * Determine if request is an HTTP API request
     *
     * @return boolean
     */
    public function isApi()
    {
        return ($this->application->router()->isHttp() &&
            (substr($this->application->router()->getRouteMatch()->getRouteString(), 0, 4) == '/api'));
    }

    /**
     * Determine if request is an HTTP web request
     *
     * @return boolean
     */
    public function isWeb()
    {
        return ($this->application->router()->isHttp() &&
            (substr($this->application->router()->getRouteMatch()->getRouteString(), 0, 4) != '/api'));
    }

    /**
     * Custom error handler method
     *
     * @param  \Exception $exception
     * @throws \Pop\View\Exception
     * @return void
     */
    public function error(\Exception $exception)
    {
        $request  = new Request();
        $response = new Response();
        $message  = $exception->getMessage();

        if (stripos($request->getHeaderValue('Accept'), 'text/html') !== false) {
            $view          = new View(__DIR__ . '/../view/exception.phtml');
            $view->title   = $message;
            $view->message = (substr($message, 0, 7) != 'Error: ') ? 'Error: ' . $message : $message;
            $sess = \Pop\Session\Session::getInstance();
            if (isset($sess->user)) {
                $view->username = $sess->user->username;
            }
            $response->addHeader('Content-Type', 'text/html');
            $response->setBody($view->render());
        } else {
            $response->addHeader('Content-Type', 'application/json');
            $response->setBody(json_encode(['error' => $message], JSON_PRETTY_PRINT) . PHP_EOL);
        }

        $response->send(500);
    }

    /**
     * Error handler
     *
     * @param  \Exception $exception
     * @return void
     */
    public function cliError(\Exception $exception)
    {
        $message = strip_tags($exception->getMessage());

        if (stripos(PHP_OS, 'win') === false) {
            $string  = "    \x1b[1;37m\x1b[41m    " . str_repeat(' ', strlen($message)) . "    \x1b[0m" . PHP_EOL;
            $string .= "    \x1b[1;37m\x1b[41m    " . $message . "    \x1b[0m" . PHP_EOL;
            $string .= "    \x1b[1;37m\x1b[41m    " . str_repeat(' ', strlen($message)) . "    \x1b[0m" . PHP_EOL . PHP_EOL;
            $string .= "    Try \x1b[1;33m./app help\x1b[0m for help" . PHP_EOL . PHP_EOL;
        } else {
            $string = $message . PHP_EOL . PHP_EOL;
            $string .= '    Try \'./app help\' for help' . PHP_EOL . PHP_EOL;
        }

        echo $string;
        echo PHP_EOL;

        exit(127);
    }

    /**
     * Initialize database service
     *
     * @param  array $database
     * @throws \Pop\Db\Adapter\Exception
     * @throws \Pop\Service\Exception
     * @return void
     */
    protected function initDb($database)
    {
        if (!empty($database['adapter'])) {
            $adapter = $database['adapter'];
            $options = [
                'database' => $database['database'],
                'username' => $database['username'],
                'password' => $database['password'],
                'host'     => $database['host'],
                'type'     => $database['type']
            ];

            $check = Db::check($adapter, $options);

            if ($check !== true) {
                throw new \Pop\Db\Adapter\Exception('Error: ' . $check);
            }

            $this->application->services()->set('database', [
                'call'   => 'Pop\Db\Db::connect',
                'params' => [
                    'adapter' => $adapter,
                    'options' => $options
                ]
            ]);

            if ($this->application->services()->isAvailable('database')) {
                Record::setDb($this->application->getService('database'));
            }
        }
    }

}