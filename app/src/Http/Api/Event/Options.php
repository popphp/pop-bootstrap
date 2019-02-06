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

/**
 * Options API event class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
 */
class Options
{
    /**
     * Check for and re-route OPTIONS requests
     *
     * @param  Application $application
     * @return void
     */
    public static function check(Application $application)
    {
        if (($application->router()->hasController()) && (null !== $application->router()->getController()->request()) &&
            ($application->router()->getController()->request()->isOptions())) {
            $application->router()->getController()->sendOptions();
            exit();
        }
    }

}