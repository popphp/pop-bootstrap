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
namespace App\Http\Web\Event;

use App\Users\Model;
use Pop\Application;
use Pop\Http\Response;

/**
 * Web session event class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
 */
class Session
{

    /**
     * Public actions
     */
    protected static $publicActions = [
        'App\Http\Web\Controller\IndexController' => [
            'login'
        ]
    ];

    /**
     * Check for the user session
     *
     * @param  Application $application
     * @throws \Pop\Db\Exception
     * @throws \Pop\Http\Exception
     * @return void
     */
    public static function check(Application $application)
    {
        if ($application->modules['pop-bootstrap']->isWeb()) {
            $acceptHeader = $application->router()->getController()->request()->getHeader('Accept');
            if (stripos($acceptHeader, 'text/html') === false) {
                $controller = new \App\Http\Api\Controller\IndexController(
                    $application, $application->router()->getController()->request(), $application->router()->getController()->response()
                );
                $controller->error(406);
            } else {
                $sess   = $application->services['session'];
                $action = $application->router()->getRouteMatch()->getAction();
                if (self::isAuth($sess) && self::isPublicAction($application->router()->getControllerClass(), $action)) {
                    Response::redirect('/');
                    exit();
                } else if (!self::isAuth($sess, false) && !self::isPublicAction($application->router()->getControllerClass(), $action)) {
                    if (isset($sess->user)) {
                        $user = new Model\User();
                        $user->logout($sess, $application->services['cookie']);
                    }
                    Response::redirect('/login');
                    exit();
                }
            }
        }
    }

    /**
     * Check if public action
     *
     * @param  string $controller
     * @param  string $action
     * @return boolean
     */
    public static function isPublicAction($controller, $action)
    {
        return (isset(self::$publicActions[$controller]) && in_array($action, self::$publicActions[$controller]));
    }

    /**
     * Check if user is auth'ed
     *
     * @param  \Pop\Session\Session $sess
     * @param  boolean              $count
     * @return boolean
     */
    public static function isAuth(\Pop\Session\Session $sess, $count = true)
    {
        return (isset($sess->user) && isset($sess->user->token) && (new Model\Token())->validateToken($sess->user->token, $count));
    }

}