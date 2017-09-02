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
namespace App\Api\Event;

use Pop\Application;

/**
 * Maintenance mode event class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.0.0
 */
class Maintenance
{

    /**
     * Check for maintenance mode
     *
     * @param  Application $application
     * @return void
     */
    public static function check(Application $application)
    {
        if (($application->config['maintenance']) && ($application->modules['pab-api']->isApi())) {
            $application->router()->getController()->error(503);
        }
    }

}