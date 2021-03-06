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
namespace App\Http\Api\Event;

use Pop\Application;
use App\Users\Model;

/**
 * Auth event class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.5.0
 */
class Auth
{

    /**
     * Public actions
     */
    protected static $publicActions = [
        'App\Http\Api\Controller\AuthController' => [
            'auth'
        ],
        'App\Http\Api\Controller\TokenController' => [
            'token', 'refresh'
        ]
    ];

    /**
     * Check authentication
     *
     * @param  Application $application
     * @return void
     */
    public static function check(Application $application)
    {
        if ($application->modules['pop-bootstrap']->isApi()) {
            $token     = new Model\Token();
            $authToken = $application->router()->getController()->request()->getHeaderValue('Authorization');
            $action    = $application->router()->getRouteMatch()->getAction();
            if (!self::isPublicAction($application->router()->getControllerClass(), $action) &&
                (!((null !== $authToken) && ($token->validateToken($authToken))))) {
                $application->router()->getController()->error(401);
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

}