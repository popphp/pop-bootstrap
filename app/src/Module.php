<?php

namespace App;

use Pop\Application;
use Pop\Db\Record;
use Pop\Http\Request;
use Pop\Http\Response;
use Pop\View\View;

class Module extends \Pop\Module\Module
{

    public function register(Application $application)
    {
        parent::register($application);

        if (null !== $this->application->router()) {
            $this->application->router()->addControllerParams(
                '*', [
                    'application' => $this->application,
                    'request'     => new Request(),
                    'response'    => new Response()
                ]
            );
        }

        if ($this->application->services()->isAvailable('database')) {
            Record::setDb($this->application->getService('database'));
        }
        
        $this->application->on('app.dispatch.pre', 'App\Event\Session::check', 1000);

        if (isset($this->config['forms'])) {
            $this->application->mergeConfig(['forms' => $this->config['forms']]);
        }
    }

    public function error(\Exception $exception)
    {
        $view = new View(__DIR__ . '/../view/exception.phtml');
        $view->title   = 'Application Error';
        $view->message = htmlentities(strip_tags($exception->getMessage()), ENT_QUOTES, 'UTF-8');

        $response = new Response();
        $response->setBody((string)$view);
        $response->send(500);
    }

}