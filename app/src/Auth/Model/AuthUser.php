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
namespace App\Auth\Model;

use App\Model\AbstractModel;
use App\Auth\Table;
use Pop\Cookie\Cookie;
use Pop\Session\Session;

/**
 * Auth model class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
 */
class AuthUser extends AbstractModel
{

    /**
     * Authenticate user
     *
     * @param  string $username
     * @param  string $password
     * @param  int    $attempts
     * @return int
     */
    public function authenticate($username, $password, $attempts = 0)
    {
        $result = 0;
        $user   = Table\AuthUsers::findOne(['username' => $username]);

        if (((int)$attempts > 0) && ((int)$user->attempts >= (int)$attempts)) {
            $result = -1;
            $user->attempts++;
            $user->save();
        } else if (isset($user->id) && ($user->active)) {
            $result = (int)password_verify($password, $user->password);
            if ($result) {
                $user->attempts = 0;
                $user->save();

                $this->data = array_merge($this->data, $user->toArray());
            } else {
                $user->attempts++;
                $user->save();
            }
        }

        return $result;
    }

    /**
     * Log in user to web session
     *
     * @param  Session $session
     * @param  Cookie  $cookie
     * @param  int     $expires
     * @return void
     */
    public function login(Session $session, Cookie $cookie, $expires)
    {
        $token    = new AuthToken();
        $userData = $token->getToken($this->id, $expires);

        $cookie->set('user', json_encode($userData));
        $session->user = new \ArrayObject($userData, \ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Log out user from web session
     *
     * @param  Session $session
     * @param  Cookie  $cookie
     * @param  int     $revoke
     * @return void
     */
    public function logout(Session $session, Cookie $cookie, $revoke = null)
    {
        if ((int)$revoke == 1) {
            $token = new AuthToken();
            $token->revoke($session->user->token);
        }

        $cookie->delete('user');
        $session->kill();
    }

    /**
     * Get all users
     *
     * @return \Pop\Db\Record\Collection
     */
    public function getAll()
    {
        $sql = Table\AuthUsers::getSql();
        $sql->select(['id', 'username', 'active', 'attempts'])->from(Table\AuthUsers::table());
        return Table\AuthUsers::query($sql);
    }

    /**
     * Get user by ID
     *
     * @param  int $id
     * @return Table\AuthUsers
     */
    public function getById($id)
    {
        return Table\AuthUsers::findById($id);
    }

    /**
     * Get user by username
     *
     * @param  string $username
     * @return Table\AuthUsers
     */
    public function getByUsername($username)
    {
        return Table\AuthUsers::findOne(['username' => $username]);
    }

    /**
     * Save new user
     *
     * @param  mixed $user
     * @return void
     */
    public function save($user)
    {
        $user = new Table\AuthUsers([
            'username' => $user['username'],
            'password' => password_hash($user['password'], PASSWORD_BCRYPT),
            'active'   => (isset($user['active'])) ? (int)$user['active'] : 0,
            'attempts' => (isset($user['attempts'])) ? (int)$user['attempts'] : 0
        ]);
        $user->save();

        $this->data['id']       = $user->id;
        $this->data['username'] = $user->username;
        $this->data['active']   = $user->active;
        $this->data['attempts'] = $user->attempts;
    }

    /**
     * Update existing user
     *
     * @param  int   $id
     * @param  mixed $user
     * @return void
     */
    public function update($id, $user)
    {
        $currentUser = Table\AuthUsers::findById($id);

        if (isset($currentUser->id)) {
            $password = (!empty($user['password'])) ?
                password_hash($user['password'], PASSWORD_BCRYPT) : $currentUser->password;

            $currentUser->username = (!empty($user['username'])) ? $user['username'] : $currentUser->username;
            $currentUser->password = $password;
            $currentUser->active   = (isset($user['active'])) ? (int)$user['active'] : $currentUser->active;
            $currentUser->attempts = (isset($user['attempts'])) ? (int)$user['attempts'] : $currentUser->attempts;
            $currentUser->save();

            $this->data['id']       = $currentUser->id;
            $this->data['username'] = $currentUser->username;
            $this->data['active']   = $currentUser->active;
            $this->data['attempts'] = $currentUser->attempts;
        }
    }

    /**
     * Delete an existing user
     *
     * @param  int   $id
     * @return void
     */
    public function delete($id)
    {
        $user = Table\AuthUsers::findById($id);

        if (isset($user->id)) {
            $user->delete();
        }
    }

    /**
     * Delete existing users
     *
     * @param  array $ids
     * @return void
     */
    public function remove(array $ids)
    {
        foreach ($ids as $id) {
            $user = Table\AuthUsers::findById($id);
            if (isset($user->id)) {
                $user->delete();
            }
        }
    }

}
