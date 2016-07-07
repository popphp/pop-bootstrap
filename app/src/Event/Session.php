<?php

namespace App\Event;

use Pop\Application;
use Pop\Http\Response;

class Session
{

    public static function check(Application $application)
    {
        $sess   = $application->getService('session');
        $action = $application->router()->getRouteMatch()->getAction();

        if (isset($sess->user) && (($action == 'login') || ($action == 'forgot') || ($action == 'verify'))) {
            Response::redirect('/');
            exit();
        } else if (!isset($sess->user) && ($action != 'login') && ($action != 'forgot') && ($action != 'verify')) {
            Response::redirect('/login');
            exit();
        }
    }

}