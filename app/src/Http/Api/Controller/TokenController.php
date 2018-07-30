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
 * Token controller class
 *
 * @category   App\Api
 * @package    App\Api
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
 */
class TokenController extends AbstractController
{

    /**
     * Token action method
     *
     * @return void
     */
    public function token()
    {
        $token     = new Model\Token();
        $authToken = $this->request->getHeader('Authorization');
        $code      = ((null !== $authToken) && ($token->validateToken($authToken))) ? 200 : 401;

        $this->send($code);
    }

    /**
     * Refresh action method
     *
     * @return void
     */
    public function refresh()
    {
        $token     = new Model\Token();
        $authToken = $this->request->getHeader('Authorization');
        $refresh   = $this->request->getParsedData('refresh');

        if (!$token->tokenExists($authToken)) {
            $this->send(401);
        } else if (empty($refresh)) {
            $this->send(400);
        } else {
            if ($token->tokenExpired($authToken)) {
                $json = $token->refreshToken($authToken, $refresh, $this->application->config()['token_expires']);
            } else {
                $json = [
                    'token'   => str_replace('Bearer ', '', $authToken),
                    'refresh' => $refresh,
                    'expires' => $token->getTokenExpiration($authToken)
                ];
            }
            $this->send(200, $json);
        }
    }

    /**
     * Revoke action method
     *
     * @return void
     */
    public function revoke()
    {
        $token     = new Model\AuthToken();
        $authToken = $this->request->getHeader('Authorization');
        if ((null !== $authToken) && ($token->validateToken($authToken))) {
            $token->revoke($authToken);
            $this->send(200);
        } else {
            $this->send(401);
        }
    }

}