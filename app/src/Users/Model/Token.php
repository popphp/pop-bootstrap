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
namespace App\Users\Model;

use App\Users\Table;
use Pop\Model\AbstractModel;

/**
 * Token model class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
 */
class Token extends AbstractModel
{

    /**
     * Static method to validate user token
     *
     * @param  string $authToken
     * @return boolean
     */
    public static function validate($authToken = null)
    {
        return ((null !== $authToken) && ((new self())->validateToken($authToken)));
    }

    /**
     * Revoke user access
     *
     * @param  string $tokenValue
     * @throws \Pop\Db\Exception
     * @return void
     */
    public function revoke($tokenValue)
    {
        if (substr($tokenValue, 0, 7) == 'Bearer ') {
            $tokenValue = substr($tokenValue, 7);
        }
        $token = new Table\Tokens();
        $token->delete(['token' => $tokenValue]);
    }

    /**
     * Get user token
     *
     * @param  int $id
     * @param  int $expires
     * @return array
     */
    public function getToken($id, $expires)
    {
        $userData = [];

        $user = Table\Users::findById($id);

        if (isset($user->id) && ($user->active)) {
            $token = Table\Tokens::findOne(['user_id' => $id]);
            if (isset($token->token)) {
                if (((int)$token->expires > 0) && (time() >= (int)$token->expires)) {
                    $token->delete();
                    $token = $this->createToken($id, $expires);
                }
            } else {
                $token = $this->createToken($id, $expires);
            }

            $userData['id']       = $user->id;
            $userData['username'] = $user->username;
            $userData['token']    = $token->token;
            $userData['refresh']  = $token->refresh;
            $userData['expires']  = $token->expires;
        }

        return $userData;
    }

    /**
     * Get user token expiration
     *
     * @param  string $tokenValue
     * @return int
     */
    public function getTokenExpiration($tokenValue)
    {
        if (substr($tokenValue, 0, 7) == 'Bearer ') {
            $tokenValue = substr($tokenValue, 7);
        }

        $token = Table\Tokens::findOne(['token' => $tokenValue]);
        return (isset($token->token)) ? $token->expires : 0;
    }

    /**
     * Refresh user token
     *
     * @param  string $tokenValue
     * @param  string $refresh
     * @param  int    $expires
     * @return array
     */
    public function refreshToken($tokenValue, $refresh, $expires)
    {
        if (substr($tokenValue, 0, 7) == 'Bearer ') {
            $tokenValue = substr($tokenValue, 7);
        }

        $token = Table\Tokens::findOne(['token' => $tokenValue]);
        $userId = $token->user_id;
        $token->delete();

        $token = $this->createToken($userId, $expires, $refresh);

        return [
            'token'   => $token->token,
            'refresh' => $token->refresh,
            'expires' => $token->expires
        ];
    }

    /**
     * Get user by token
     *
     * @param  string $tokenValue
     * @return array
     */
    public function getUserByToken($tokenValue)
    {
        if (substr($tokenValue, 0, 7) == 'Bearer ') {
            $tokenValue = substr($tokenValue, 7);
        }

        $userData = [];
        $token    = Table\Tokens::findOne(['token' => $tokenValue]);

        if (!empty($token->user_id)) {
            $user = Table\Users::findById($token->user_id);
            if (isset($user->id)) {
                $userData['id']       = $user->id;
                $userData['username'] = $user->username;
                $userData['token']    = $token->token;
                $userData['refresh']  = $token->refresh;
                $userData['expires']  = $token->expires;
            }
        }

        return $userData;
    }

    /**
     * Determined if user bearing the token is active
     *
     * @param  string $tokenValue
     * @return boolean
     */
    public function isUserActive($tokenValue)
    {
        if (substr($tokenValue, 0, 7) == 'Bearer ') {
            $tokenValue = substr($tokenValue, 7);
        }

        $result = false;
        $token  = Table\Tokens::findOne(['token' => $tokenValue]);
        if (!empty($token->user_id)) {
            $user   = Table\Users::findById($token->user_id);
            $result = (isset($user->id) && ($user->active));
        }

        return $result;
    }

    /**
     * Validate user token
     *
     * @param  string  $tokenValue
     * @param  boolean $count
     * @return boolean
     */
    public function validateToken($tokenValue, $count = true)
    {
        return (!($this->tokenExpired($tokenValue, $count)) && ($this->isUserActive($tokenValue)));
    }

    /**
     * Determined if the token exists
     *
     * @param  string $tokenValue
     * @return boolean
     */
    public function tokenExists($tokenValue)
    {
        if (substr($tokenValue, 0, 7) == 'Bearer ') {
            $tokenValue = substr($tokenValue, 7);
        }

        return (isset(Table\Tokens::findOne(['token' => $tokenValue])->token));
    }

    /**
     * Determined if the token is expired
     *
     * @param  string  $tokenValue
     * @param  boolean $count
     * @return boolean
     */
    public function tokenExpired($tokenValue, $count = true)
    {
        if (substr($tokenValue, 0, 7) == 'Bearer ') {
            $tokenValue = substr($tokenValue, 7);
        }

        $result = true;
        $token  = Table\Tokens::findOne(['token' => $tokenValue]);
        if (isset($token->token)) {
            $result = !(((int)$token->expires == 0) || (time() < (int)$token->expires));
            if ((!$result) && ($count)) {
                $token->requests++;
                $token->save();
            }
        }

        return $result;
    }

    /**
     * Create token
     *
     * @param  int $id
     * @param  int $expires
     * @param  string $refresh
     * @throws \Pop\Db\Exception
     * @return Table\Tokens
     */
    public function createToken($id, $expires, $refresh = null)
    {
        if (null === $refresh) {
            $refresh = sha1($id . '-refresh-' . (time() + (int)$expires));
        }

        $token = new Table\Tokens([
            'token'   => sha1($id . '-' . time()),
            'user_id' => $id,
            'refresh' => $refresh,
            'expires' => ((int)$expires > 0) ? time() + (int)$expires : 0
        ]);
        $token->save();

        return $token;
    }

}