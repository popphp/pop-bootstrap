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
namespace App\Http\Controller;

use Pop\View\View;

/**
 * Index controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.5.0
 */
class IndexController extends AbstractController
{

    /**
     * Error handler method
     *
     * @throws \Pop\Http\Exception
     * @throws \Pop\View\Exception
     * @return void
     */
    public function error()
    {
        $response = ['code' => 404, 'message' => 'Not Found'];

        if (stripos($this->request->getHeaderValue('Accept'), 'text/html') !== false) {
            $view = new View(__DIR__ . '/../../../view/error.phtml', $response);
            $view->title = 'Error: ' .  $response['code'] . ' ' . $response['message'];
            if ($this->application->services->isLoaded('session')) {
                $sess = $this->application->services['session'];
                $view->username = $sess->user->username;
            }
            $this->response->addHeader('Content-Type', 'text/html');
            $this->response->setBody($view->render());
        } else {
            $this->response->addHeader('Content-Type', 'application/json');
            $this->response->setBody(json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL);
        }

        $this->response->send(404);
        exit();
    }

}