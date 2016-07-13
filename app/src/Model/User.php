<?php

namespace App\Model;

use App\Table;
use Pop\Crypt\Bcrypt;
use Pop\Db\Sql;

class User extends AbstractModel
{

    public function getAll($roleId = null, $username = null, array $deniedRoles = null, $limit = null, $page = null, $sort = null)
    {
        $sql = Table\Users::sql();

        $sql->select([
            'id'           => DB_PREFIX . 'users.id',
            'user_role_id' => DB_PREFIX . 'users.role_id',
            'username'     => DB_PREFIX . 'users.username',
            'email'        => DB_PREFIX . 'users.email',
            'active'       => DB_PREFIX . 'users.active',
            'verified'     => DB_PREFIX . 'users.verified',
            'role_id'      => DB_PREFIX . 'roles.id',
            'role_name'    => DB_PREFIX . 'roles.name'
        ])->join(DB_PREFIX . 'roles', [DB_PREFIX . 'users.role_id' => DB_PREFIX . 'roles.id']);

        if (null !== $limit) {
            $page = ((null !== $page) && ((int)$page > 1)) ?
                ($page * $limit) - $limit : null;

            $sql->select()->offset($page)->limit($limit);
        }
        $params = [];
        $order  = $this->getSortOrder($sort, $page);
        $by     = explode(' ', $order);
        $sql->select()->orderBy($by[0], $by[1]);

        if (null !== $username) {
            $sql->select()->where('username LIKE :username');
            $params['username'] = $username . '%';
        }

        if (is_array($deniedRoles) && (count($deniedRoles) > 0)) {
            foreach ($deniedRoles as $key => $denied) {
                $sql->select()->where('role_id != :role_id' . ($key + 1));
                $params['role_id' . ($key + 1)] = $denied;
            }
        }

        if (null !== $roleId) {
            if ($roleId == 0) {
                $sql->select()->where(DB_PREFIX . 'users.role_id IS NULL');
                $rows = (count($params) > 0) ?
                    Table\Users::execute((string)$sql, $params, Table\Users::ROW_AS_OBJECT)->rows() :
                    Table\Users::query((string)$sql, Table\Users::ROW_AS_OBJECT)->rows();
            } else {
                $sql->select()->where(DB_PREFIX . 'users.role_id = :role_id');
                $params['role_id'] = $roleId;
                $rows = Table\Users::execute((string)$sql, $params, Table\Users::ROW_AS_OBJECT)->rows();
            }
        } else {
            $rows = (count($params) > 0) ?
                Table\Users::execute((string)$sql, $params, Table\Users::ROW_AS_OBJECT)->rows() :
                Table\Users::query((string)$sql, Table\Users::ROW_AS_OBJECT)->rows();
        }

        return $rows;
    }

    public function getRoles()
    {
        $roles    = Table\Roles::findAll(null, Table\Roles::ROW_AS_OBJECT)->rows();
        $rolesAry = [];

        foreach ($roles as $role) {
            $rolesAry[$role->id] = $role->name;
        }

        $rolesAry[0] = '[Blocked]';
        return $rolesAry;
    }

    public function getByRoleId($rid)
    {
        return Table\Users::findBy(['role_id' => (int)$rid], null, Table\Roles::ROW_AS_OBJECT)->rows();
    }

    public function getByRole($name)
    {
        $role  = Table\Roles::findBy(['name' => $name], null, Table\Roles::ROW_AS_OBJECT);
        $users = [];
        if (isset($role->id)) {
            $users = Table\Users::findBy(['role_id' => $role->id], null, Table\Roles::ROW_AS_OBJECT)->rows();
        }

        return $users;
    }

    public function getById($id)
    {
        $user = Table\Users::findById((int)$id);
        if (isset($user->id)) {
            $this->data['id']              = $user->id;
            $this->data['role_id']         = $user->role_id;
            $this->data['username']        = $user->username;
            $this->data['email']           = $user->email;
            $this->data['active']          = $user->active;
            $this->data['verified']        = $user->verified;
            $this->data['last_ip']         = $user->last_ip;
            $this->data['last_ua']         = $user->last_ua;
            $this->data['total_logins']    = $user->total_logins;
            $this->data['failed_attempts'] = $user->failed_attempts;
        }
    }

    public function save(array $fields, $title)
    {
        $user = new Table\Users([
            'role_id'    => $fields['role_id'],
            'username'   => $fields['username'],
            'password'   => (new Bcrypt())->create($fields['password1']),
            'email'      => (isset($fields['email']) ? $fields['email'] : null),
            'active'     => (int)$fields['active'],
            'verified'   => (int)$fields['verified']
        ]);
        $user->save();

        $this->data = array_merge($this->data, $user->getColumns());

        if ((!$user->verified) && !empty($user->email)) {
            $notify = new Notification();
            $notify->sendVerification($user, $title);
        }
    }

    public function update(array $fields, $title, \Pop\Session\Session $sess = null)
    {
        $user = Table\Users::findById((int)$fields['id']);
        if (isset($user->id)) {
            $oldRoleId = $user->role_id;
            $oldActive = $user->active;

            $user->role_id    = (isset($fields['role_id']) ? $fields['role_id'] : $user->role_id);
            $user->username   = $fields['username'];
            $user->password   = (!empty($fields['password1'])) ?
                (new Bcrypt())->create($fields['password1']) : $user->password;
            $user->email      = (isset($fields['email']) ? $fields['email'] : $user->email);
            $user->active     = (isset($fields['active']) ? (int)$fields['active'] : $user->active);
            $user->verified   = (isset($fields['verified']) ? (int)$fields['verified'] : $user->verified);

            $user->save();

            if ((null !== $sess) && ($sess->user->id == $user->id)) {
                $sess->user->username = $user->username;
                $sess->user->email    = $user->email;
            }

            $this->data = array_merge($this->data, $user->getColumns());

            if ((((null === $oldRoleId) && (null !== $user->role_id)) || ((!($oldActive) && ($user->active)))) && !empty($user->email)) {
                $notify = new Notification();
                $notify->sendApproval($user, $title);
            }
        }
    }

    public function process(array $post, $title)
    {
        if (isset($post['process_users'])) {
            foreach ($post['process_users'] as $id) {
                $user = Table\Users::findById((int)$id);
                if (isset($user->id)) {
                    switch ((int)$post['user_process_action']) {
                        case 1:
                            $user->active = 1;
                            $user->save();
                            $notify = new Notification();
                            $notify->sendApproval($user, $title);
                            break;
                        case 0:
                            $user->active = 0;
                            $user->save();
                            break;
                        case -1:
                            $user->delete();
                            break;
                    }
                }
            }
        }
    }

    public function login($user, $sess)
    {
        $user->failed_attempts = 0;
        $user->total_logins++;
        $user->save();

        $ip = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
        $ua = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');

        $session = new Session();
        $session->login($user->id, $ip, $ua);
        $session->start($user->id, $sess->getId(), $ip, $ua);

        $role = Table\Roles::findById($user->role_id);

        $sess->user = new \ArrayObject([
            'id'           => $user->id,
            'sess_id'      => $session->id,
            'role_id'      => $user->role_id,
            'role'         => $role->name,
            'username'     => $user->username,
            'email'        => $user->email,
            'last_ip'      => $user->last_ip,
            'last_ua'      => $user->last_ua,
            'total_logins' => $user->total_logins
        ], \ArrayObject::ARRAY_AS_PROPS);
    }

    public function failed($user)
    {
        $user->failed_attempts++;
        $user->save();
    }

    public function logout($sess)
    {
        $user = Table\Users::findById($sess->user->id);

        $session = new Session();
        $session->clear($sess->user->sess_id, $sess->user->id, $sess->getId());

        if (isset($user->id)) {
            $user->last_ip = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null);
            $user->last_ua = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null);
            $user->save();
        }
    }

    public function verify($id, $hash)
    {
        $result = false;
        $user   = Table\Users::findById((int)$id);

        if (isset($user->id) && ($hash == sha1($user->email))) {
            $user->verified = 1;
            $user->save();
            $this->data['id'] = $user->id;
            $result = true;
        }

        return $result;
    }

    public function forgot(array $fields, $title)
    {
        $user = Table\Users::findBy(['email' => $fields['email']]);
        if (isset($user->id)) {
            $this->data['id'] = $user->id;
            $notify = new Notification();
            $notify->sendReset($user, $title);
        }
    }

    public function hasPages($limit, $roleId = null, $username = null, array $deniedRoles = [])
    {
        $params = [];
        $sql    = Table\Users::sql();
        $sql->select();

        if (null !== $username) {
            $sql->select()->where('username LIKE :username');
            $params['username'] = $username . '%';
        }

        if (null !== $roleId) {
            $sql->select()->where('role_id = :role_id');
            $params['role_id'] = $roleId;
        }

        if (count($deniedRoles) > 0) {
            foreach ($deniedRoles as $key => $denied) {
                $sql->select()->where('role_id != :role_id' . ($key + 1));
                $params['role_id' . ($key + 1)] = $denied;
            }
        }

        if (count($params) > 0) {
            return (Table\Users::execute((string)$sql, $params, Table\Users::ROW_AS_ARRAY)->count() > $limit);
        } else {
            return (Table\Users::findAll(null, Table\Users::ROW_AS_ARRAY)->count() > $limit);
        }
    }

    public function getCount($roleId = null, $username = null, array $deniedRoles = [])
    {
        $params = [];
        $sql    = Table\Users::sql();
        $sql->select();

        if (null !== $username) {
            $sql->select()->where('username LIKE :username');
            $params['username'] = $username . '%';
        }

        if (null !== $roleId) {
            $sql->select()->where('role_id = :role_id');
            $params['role_id'] = $roleId;
        }

        if (count($deniedRoles) > 0) {
            foreach ($deniedRoles as $key => $denied) {
                $sql->select()->where('role_id != :role_id' . ($key + 1));
                $params['role_id' . ($key + 1)] = $denied;
            }
        }

        if (count($params) > 0) {
            return Table\Users::execute((string)$sql, $params, Table\Users::ROW_AS_ARRAY)->count();
        } else {
            return Table\Users::findAll(null, Table\Users::ROW_AS_ARRAY)->count();
        }
    }

}