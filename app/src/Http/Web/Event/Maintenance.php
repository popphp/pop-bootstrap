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
namespace App\Http\Web\Event;

use Pop\Application;
use Pop\Http\Response;
use Pop\View\View;

/**
 * Maintenance mode web event class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
 */
class Maintenance
{

    /**
     * Check for maintenance mode
     *
     * @param  Application $application
     * @throws \Pop\Http\Exception
     * @throws \Pop\View\Exception
     * @return void
     */
    public static function check(Application $application)
    {
        if (($application->config()['maintenance']) && ($application->modules['pop-bootstrap']->isWeb())) {
            $acceptHeader = $application->router()->getController()->request()->getHeader('Accept');
            if (stripos($acceptHeader, 'text/html') === false) {
                $controller = new \App\Http\Api\Controller\IndexController(
                    $application, $application->router()->getController()->request(), $application->router()->getController()->response()
                );
                $controller->error(406);
            } else {
                $view     = new View(__DIR__ . '/../../../../view/maintenance.phtml');
                $view->title = 'Maintenance';
                if (($application->services->isAvailable('session')) && isset($application->services['session']->user)) {
                    $view->username = $application->services['session']->user->username;
                }
                $response = new Response();
                $response->setCode(503);
                $response->setBody($view->render());
                $response->sendAndExit();
            }
        }
    }

}