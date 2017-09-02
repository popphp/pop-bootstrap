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
namespace App\Controller;

use Pop\View\View;

/**
 * Index controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.0.0
 */
class IndexController extends AbstractController
{

    /**
     * Error handler method
     *
     * @return void
     */
    public function error()
    {
        $response = ['code' => 404, 'message' => 'Not Found'];

        if (stripos($this->request->getHeader('Accept'), 'text/html') !== false) {
            $view = new View(__DIR__ . '/../../view/error.phtml', $response);
            $this->response->setHeader('Content-Type', 'text/html');
            $this->response->setBody($view->render());
        } else {
            $this->response->setHeader('Content-Type', 'application/json');
            $this->response->setBody(json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL);
        }

        $this->response->send(404);
    }

}