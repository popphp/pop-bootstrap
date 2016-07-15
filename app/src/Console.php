<?php
/**
 * Pop Web Bootstrap Application Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace App;

use Pop\Application;
use Pop\Db\Record;

/**
 * Console module class
 *
 * @category   Pop_Bootstrap
 * @package    Pop_Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.0
 */
class Console extends \Pop\Module\Module
{

    /**
     * Register module
     *
     * @param  Application $application
     * @throws Exception
     * @return Module
     */
    public function register(Application $application)
    {
        parent::register($application);

        // Add route params for the controllers
        if (null !== $this->application->router()) {
            $this->application->router()->addControllerParams(
                '*', [
                    'application' => $this->application,
                    'console'     => new \Pop\Console\Console(120, '    ')
                ]
            );
        }
        
        if (!empty($this->application->config()['database']) && !empty($this->application->config()['database']['adapter'])) {
            $adapter = $this->application->config()['database']['adapter'];
            $options = [
                'database' => $this->application->config()['database']['database'],
                'username' => $this->application->config()['database']['username'],
                'password' => $this->application->config()['database']['password'],
                'host'     => $this->application->config()['database']['host'],
                'type'     => $this->application->config()['database']['type']
            ];

            $check = \Pop\Db\Db::check($adapter, $options);

            if (null !== $check) {
                throw new Exception('DB ' . $check);
            }

            $this->application->services()->set('database', [
                'call'   => 'Pop\Db\Db::connect',
                'params' => [
                    'adapter' => $adapter,
                    'options' => $options
                ]
            ]);
        }

        if ($this->application->services()->isAvailable('database')) {
            Record::setDb($this->application->getService('database'));
        }

        // Set up triggers to check the application session
        $this->application->on('app.route.pre', function(){
            if (isset($_SERVER['argv'][1])) {
                echo PHP_EOL . '    App Console' . PHP_EOL;
                echo '    ===========' . PHP_EOL . PHP_EOL;
            }
        }, 1000);
        $this->application->on('app.dispatch.post', function(){
            echo PHP_EOL;
        }, 1000);

        return $this;
    }

    /**
     * Error handler
     *
     * @param  \Exception $exception
     * @return void
     */
    public function error(\Exception $exception)
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
        exit(127);
    }

}