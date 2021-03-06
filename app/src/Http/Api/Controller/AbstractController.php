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
namespace App\Http\Api\Controller;

use Pop\Http\Server\Response;

/**
 * Abstract API controller class
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
     * Send response
     *
     * @param  int $code
     * @param  mixed $body
     * @param  string $message
     * @param  array $headers
     * @return void
     */
    public function send($code = 200, $body = null, $message = null, array $headers = null)
    {
        $this->application->trigger('app.send.pre', ['controller' => $this]);

        $this->response->setCode($code);

        if (null !== $message) {
            $this->response->setMessage($message);
        }

        $this->response->addHeader('Access-Control-Allow-Origin', '*')
             ->addHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type')
             ->addHeader('Access-Control-Allow-Methods', 'HEAD, OPTIONS, GET, PUT, POST, PATCH, DELETE')
             ->addHeader('Content-Type', 'application/json');

        $responseBody = (!empty($body)) ? json_encode($body, JSON_PRETTY_PRINT) : '';

        $this->response->setBody($responseBody . PHP_EOL . PHP_EOL);

        $this->application->trigger('app.send.post', ['controller' => $this]);
        $this->response->send(null, $headers);
    }

    /**
     * Send OPTIONS response
     *
     * @param  int $code
     * @param  string $message
     * @param  array $headers
     * @return void
     */
    public function sendOptions($code = 200, $message = null, array $headers = null)
    {
        $this->send($code, '', $message, $headers);
    }

    /**
     * Custom error handler method
     *
     * @param  int $code
     * @param  string $message
     * @throws \Pop\Http\Exception
     * @return void
     */
    public function error($code = 404, $message = null)
    {
        if (null === $message) {
            $message = Response::getMessageFromCode($code);
        }

        $responseBody = json_encode(['code' => $code, 'message' => $message], JSON_PRETTY_PRINT) . PHP_EOL . PHP_EOL;

        $this->response->setCode($code)
            ->setMessage($message)
            ->addHeader('Access-Control-Allow-Origin', '*')
            ->addHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type')
            ->addHeader('Access-Control-Allow-Methods', 'HEAD, OPTIONS, GET, PUT, POST, PATCH, DELETE')
            ->addHeader('Content-Type', 'application/json')
            ->setBody($responseBody)
            ->sendAndExit();
    }

}