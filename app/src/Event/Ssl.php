<?php

namespace App\Event;

use Pop\Application;
use Pop\Http\Response;

class Ssl
{

    public static function check(Application $application)
    {
        if (($application->config()['force_ssl']) && ($_SERVER['SERVER_PORT'] != '443')) {
            Response::redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit();
        }
    }

}