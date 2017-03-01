<?php
/**
 * Pop Web Bootstrap Application Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace App\Model;

use App\Table;

/**
 * Session model class
 *
 * @category   Pop\Bootstrap
 * @package    Pop\Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
class Session extends AbstractModel
{

    /**
     * Get user sessions
     *
     * @param  string $username
     * @param  int    $limit
     * @param  int    $page
     * @param  string $sort
     * @return \Pop\Db\Record\Collection
     */
    public function getAll($username = null, $limit = null, $page = null, $sort = null)
    {
        $sql = Table\UserSessions::sql();

        $sql->select([
            'id'           => 'user_sessions.id',
            'ip'           => 'user_sessions.ip',
            'ua'           => 'user_sessions.ua',
            'start'        => 'user_sessions.start',
            'username'     => 'users.username',
            'role_name'    => 'roles.name'
        ])->from(Table\UserSessions::table())
          ->leftJoin('users', ['users.id' => 'user_sessions.user_id'])
          ->leftJoin('roles', ['users.role_id' => 'roles.id']);

        if (null !== $limit) {
            $page = ((null !== $page) && ((int)$page > 1)) ?
                ($page * $limit) - $limit : null;

            $sql->select()->offset($page)->limit($limit);
        }

        $params = [];
        $order  = (null !== $sort) ? $this->getSortOrder($sort, $page) : 'start DESC';
        $by     = explode(' ', $order);
        $sql->select()->orderBy($by[0], $by[1]);

        if (null !== $username) {
            $sql->select()->where('username LIKE :username');
            $params['username'] = $username . '%';
        }

        return (count($params) > 0) ?
            Table\UserSessions::execute((string)$sql, $params) :
            Table\UserSessions::query((string)$sql);
    }

    /**
     * Get user logins
     *
     * @param  string $username
     * @param  int    $limit
     * @param  int    $page
     * @param  string $sort
     * @return \Pop\Db\Record\Collection
     */
    public function getLogins($username = null, $limit = null, $page = null, $sort = null)
    {
        $sql = Table\UserLogins::sql();

        $sql->select([
            'id'           => 'user_logins.id',
            'ip'           => 'user_logins.ip',
            'ua'           => 'user_logins.ua',
            'timestamp'    => 'user_logins.timestamp',
            'username'     => 'users.username',
            'role_name'    => 'roles.name'
        ])->from(Table\UserLogins::table())
          ->leftJoin('users', ['users.id' => 'user_logins.user_id'])
          ->leftJoin('roles', ['users.role_id' => 'roles.id']);

        if (null !== $limit) {
            $page = ((null !== $page) && ((int)$page > 1)) ?
                ($page * $limit) - $limit : null;

            $sql->select()->offset($page)->limit($limit);
        }

        $params = [];
        $order  = (null !== $sort) ? $this->getSortOrder($sort, $page) : 'timestamp DESC';
        $by     = explode(' ', $order);
        $sql->select()->orderBy($by[0], $by[1]);

        if (null !== $username) {
            $sql->select()->where('username LIKE :username');
            $params['username'] = $username . '%';
        }

        return (count($params) > 0) ?
            Table\UserLogins::execute((string)$sql, $params) :
            Table\UserLogins::query((string)$sql);
    }

    /**
     * Get all users
     *
     * @return \Pop\Db\Record\Collection
     */
    public function getAllUsers()
    {
        return Table\Users::findAll();
    }

    /**
     * Validate user session
     *
     * @param  mixed $user
     * @param  array $config
     * @return boolean
     */
    public function validate($user, $config)
    {
        $result = true;

        // Check for multiple sessions
        if ((!$config['multiple_sessions']) && isset(Table\UserSessions::findBy(['user_id' => $user->id])->id)) {
            $result = false;
        }
        // Check for too many failed attempts
        if (($config['login_attempts'] > 0) && ($user->failed_attempts >= $config['login_attempts'])) {
            $result = false;
        }

        return $result;
    }

    /**
     * Login a user
     *
     * @param  int    $uid
     * @param  string $ip
     * @param  string $ua
     * @return void
     */
    public function login($uid, $ip = '', $ua = '')
    {
        $login = new Table\UserLogins([
            'user_id'   => $uid,
            'ip'        => $ip,
            'ua'        => $ua,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        $login->save();

        $this->data = array_merge($this->data, $login->toArray());
    }

    /**
     * Start a user session
     *
     * @param  int    $uid
     * @param  int    $sid
     * @param  string $ip
     * @param  string $ua
     * @return void
     */
    public function start($uid, $sid, $ip = '', $ua = '')
    {
        $sess = new Table\UserSessions([
            'user_id'    => $uid,
            'session_id' => $sid,
            'ip'         => $ip,
            'ua'         => $ua,
            'start'      => time()
        ]);
        $sess->save();

        $this->data = array_merge($this->data, $sess->toArray());
    }

    /**
     * Clear a user session
     *
     * @param  int $id
     * @param  int $uid
     * @param  int $sid
     * @return void
     */
    public function clear($id, $uid, $sid)
    {
        $sess = Table\UserSessions::findOne([
            'id'         => $id,
            'user_id'    => $uid,
            'session_id' => $sid
        ]);

        if (isset($sess->id)) {
            $sess->delete();
        }
    }

    /**
     * Remove sessions
     *
     * @param  array $rm
     * @return void
     */
    public function remove(array $rm)
    {
        foreach ($rm as $id) {
            $session = Table\UserSessions::findById((int)$id);
            if (isset($session->id)) {
                $session->delete();
            }
        }
    }

    /**
     * Clear user sessions
     *
     * @param  int $sessId
     * @param  int $uid
     * @return void
     */
    public function clearSessions($sessId, $uid = null)
    {
        $sql    = Table\UserSessions::getSql();
        $params = ['id' => $sessId];

        if (null !== $uid) {
            $sql->delete(Table\UserSessions::table())
                ->where('id != :id')
                ->where('user_id = :user_id');
            $params['user_id'] = (int)$uid;
        } else {
            $sql->delete(Table\UserSessions::table())
                ->where('id != :id');
        }

        Table\UserSessions::execute((string)$sql, $params);
    }

    /**
     * Remove logins
     *
     * @param  array $rm
     * @return void
     */
    public function removeLogins(array $rm)
    {
        foreach ($rm as $id) {
            $login = Table\UserLogins::findById((int)$id);
            if (isset($login->id)) {
                $login->delete();
            }
        }
    }

    /**
     * Clear user logins
     *
     * @param  int $uid
     * @return void
     */
    public function clearLogins($uid = null)
    {
        if (null !== $uid) {
            $logins = new Table\UserLogins();
            $logins->delete(['user_id' => (int)$uid]);
        } else {
            Table\UserLogins::query('TRUNCATE TABLE ' . 'user_logins');
        }
    }

    /**
     * Determine if list of user sessions has pages
     *
     * @param  int    $limit
     * @param  string $username
     * @return boolean
     */
    public function hasPages($limit, $username = null)
    {
        if (null !== $username) {
            $sql = Table\UserSessions::sql();

            $sql->select([
                'id'           => 'user_sessions.id',
                'username'     => 'users.username',
            ])->from(Table\UserSessions::table())
              ->leftJoin('users', ['users.id' => 'user_sessions.user_id']);

            $sql->select()->where('username LIKE :username');
            $params = ['username' => $username . '%'];

            return (Table\UserSessions::execute((string)$sql, $params, Table\UserSessions::AS_ARRAY)->count() > $limit);
        } else {
            return (Table\UserSessions::findAll(null, Table\UserSessions::AS_ARRAY)->count() > $limit);
        }
    }

    /**
     * Get count of user sessions
     *
     * @param  string $username
     * @return int
     */
    public function getCount($username = null)
    {
        if (null !== $username) {
            $sql = Table\UserSessions::sql();
            $sql->select([
                'id'           => 'user_sessions.id',
                'username'     => 'users.username',
            ])->from(Table\UserSessions::table())
              ->leftJoin('users', ['users.id' => 'user_sessions.user_id']);

            $sql->select()->where('username LIKE :username');
            $params = ['username' => $username . '%'];

            return Table\UserSessions::execute((string)$sql, $params, Table\UserSessions::AS_ARRAY)->count();
        } else {
            return Table\UserSessions::findAll(null, Table\UserSessions::AS_ARRAY)->count();
        }
    }

    /**
     * Determine if list of user logins has pages
     *
     * @param  int    $limit
     * @param  string $username
     * @return boolean
     */
    public function hasLoginPages($limit, $username = null)
    {
        if (null !== $username) {
            $sql = Table\UserLogins::sql();

            $sql->select([
                'id'           => 'user_logins.id',
                'username'     => 'users.username',
            ])->from(Table\UserLogins::table())
              ->leftJoin('users', ['users.id' => 'user_logins.user_id']);

            $sql->select()->where('username LIKE :username');
            $params = ['username' => $username . '%'];

            return (Table\UserLogins::execute((string)$sql, $params, Table\UserLogins::AS_ARRAY)->count() > $limit);
        } else {
            return (Table\UserLogins::findAll(null, Table\UserLogins::AS_ARRAY)->count() > $limit);
        }
    }

    /**
     * Get count of user logins
     *
     * @param  string $username
     * @return int
     */
    public function getLoginCount($username = null)
    {
        if (null !== $username) {
            $sql = Table\UserLogins::sql();
            $sql->select([
                'id'           => 'user_logins.id',
                'username'     => 'users.username',
            ])->from(Table\UserLogins::table())
              ->leftJoin('users', ['users.id' => 'user_logins.user_id']);

            $sql->select()->where('username LIKE :username');
            $params = ['username' => $username . '%'];

            return Table\UserLogins::execute((string)$sql, $params, Table\UserLogins::AS_ARRAY)->count();
        } else {
            return Table\UserLogins::findAll(null, Table\UserLogins::AS_ARRAY)->count();
        }
    }

}