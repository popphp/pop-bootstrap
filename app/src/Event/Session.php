<?php

namespace App\Event;

use App\Model;
use App\Table;
use Pop\Application;
use Pop\Http\Response;

class Session
{

    public static function check(Application $application)
    {
        $sess   = $application->getService('session');
        $action = $application->router()->getRouteMatch()->getAction();

        if (isset($sess->user) && isset($sess->user->sess_id) && !isset(Table\UserSessions::findById($sess->user->sess_id)->id)) {
            $user = new Model\User();
            $user->logout($sess);
            unset($sess->user);
            $sess->setRequestValue('expired', true);
            Response::redirect('/login');
            exit();
        } else if (isset($sess->user) && (($action == 'login') || ($action == 'forgot') || ($action == 'verify'))) {
            Response::redirect('/');
            exit();
        } else if (!isset($sess->user) && ($action != 'login') && ($action != 'forgot') && ($action != 'verify')) {
            Response::redirect('/login');
            exit();
        }
    }

}