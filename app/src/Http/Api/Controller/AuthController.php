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
namespace App\Http\Api\Controller;

use App\Users\Model;

/**
 * Auth controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
 */
class AuthController extends AbstractController
{

    /**
     * Auth action method
     *
     * @throws \Pop\Http\Exception
     * @return void
     */
    public function auth()
    {
        $user      = new Model\User();
        $data      = $this->request->getParsedData();
        $code      = 401;
        $response  = null;
        $username  = null;
        $password  = null;
        $authToken = $this->request->getHeader('Authorization');

        if (isset($data['username']) && isset($data['password'])) {
            $username = $data['username'];
            $password = $data['password'];
        } else if (null !== $authToken) {
            if (substr($authToken, 0, 6) == 'Basic ') {
                $authToken = substr($authToken, 6);
            }
            $authToken = base64_decode(trim($authToken));
            if (strpos($authToken, ':') !== false) {
                $authTokenAry = explode(':', $authToken);
                $username = $authTokenAry[0];
                $password = $authTokenAry[1];
            }
        }

        if ((null !== $username) && (null !== $password)) {
            $result = $user->authenticate($username, $password, $this->application->config()['auth_attempts']);
            if ($result == 1) {
                $token    = new Model\Token();
                $code     = 200;
                $response = $token->getToken($user->id, $this->application->config()['token_expires']);
            } else if ($result == -1) {
                $code = 429;
            }
        }

        $this->send($code, $response);
    }

}