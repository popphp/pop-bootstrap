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
namespace App\Http\Web\Controller;

use Pop\Http\Server\Response;
use Pop\View\View;

/**
 * Abstract web controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.5.0
 */
abstract class AbstractController extends \App\Http\Controller\AbstractController
{

    /**
     * View path
     * @var string
     */
    protected $viewPath = __DIR__ . '/../../../../view';

    /**
     * View object
     * @var \Pop\View\View
     */
    protected $view = null;

    /**
     * Get view object
     *
     * @return View
     */
    public function view()
    {
        return $this->view;
    }

    /**
     * Determine if the view object has been created
     *
     * @return boolean
     */
    public function hasView()
    {
        return (null !== $this->view);
    }

    /**
     * Send response
     *
     * @param  string $body
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     */
    public function send($body = null, $code = 200, $message = null, array $headers = null)
    {
        $this->application->trigger('app.send.pre', ['controller' => $this]);

        if ((null === $body) && (null !== $this->view)) {
            $body = $this->view->render();
        }

        if (null !== $message) {
            $this->response->setMessage($message);
        }

        $this->response->setCode($code);

        if (!$this->response->hasHeader('Content-Type')) {
            $this->response->addHeader('Content-Type', 'text/html');
        }

        $this->response->setBody($body . PHP_EOL . PHP_EOL);

        $this->application->trigger('app.send.post', ['controller' => $this]);

        $this->response->send(null, $headers);
    }

    /**
     * Redirect response
     *
     * @param  string $url
     * @param  string $code
     * @param  string $version
     * @throws \Pop\Http\Exception
     * @return void
     */
    public function redirect($url, $code = '302', $version = '1.1')
    {
        Response::redirect($url, $code, $version);
        exit();
    }

    /**
     * Prepare view
     *
     * @param  string $template
     * @return void
     */
    protected function prepareView($template)
    {
        $this->view = new View($this->viewPath . '/' . $template);
    }

}