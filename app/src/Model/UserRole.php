<?php

namespace App\Model;

use App\Table;

class UserRole extends AbstractModel
{

    /**
     * Get all user roles
     *
     * @param  int    $limit
     * @param  int    $page
     * @param  string $sort
     * @return array
     */
    public function getAll($limit = null, $page = null, $sort = null)
    {
        $order = $this->getSortOrder($sort, $page);

        if (null !== $limit) {
            $page = ((null !== $page) && ((int)$page > 1)) ?
                ($page * $limit) - $limit : null;

            return Table\UserRoles::findAll([
                'offset' => $page,
                'limit'  => $limit,
                'order'  => $order
            ])->rows();
        } else {
            return Table\UserRoles::findAll([
                'order'  => $order
            ])->rows();
        }
    }

    /**
     * Get user role by ID
     *
     * @param  int $id
     * @return void
     */
    public function getById($id)
    {
        $role = Table\UserRoles::findById((int)$id);
        if (isset($role->id)) {
            $data = $role->getColumns();
            $data['role_parent_id'] = $data['parent_id'];
            $this->data = array_merge($this->data, $data);
        }
    }

    /**
     * Save new user role
     *
     * @param  array $post
     * @return void
     */
    public function save(array $post)
    {
        $role = new Table\UserRoles([
            'parent_id'         => ($post['role_parent_id'] != '----') ? (int)$post['role_parent_id'] : null,
            'name'              => html_entity_decode($post['name'], ENT_QUOTES, 'UTF-8'),
            'permissions'       => serialize($this->getPermissions($post))
        ]);
        $role->save();

        $this->data = array_merge($this->data, $role->getColumns());
    }

    /**
     * Update an existing user role
     *
     * @param  array                $post
     * @param  \Pop\Session\Session $sess
     * @return void
     */
    public function update(array $post, $sess = null)
    {
        $role = Table\UserRoles::findById((int)$post['id']);
        if (isset($role->id)) {
            $role->parent_id         = ($post['role_parent_id'] != '----') ? (int)$post['role_parent_id'] : null;
            $role->name              = html_entity_decode($post['name'], ENT_QUOTES, 'UTF-8');
            $role->permissions       = serialize($this->getPermissions($post));
            $role->save();

            $this->data = array_merge($this->data, $role->getColumns());

            if ((null !== $sess) && isset($sess->user) && ($sess->user->role_id == $role->id)) {
                $sess->user->role = $role->name;
            }
        }
    }

    /**
     * Remove a user role
     *
     * @param  array $post
     * @return void
     */
    public function remove(array $post)
    {
        if (isset($post['rm_roles'])) {
            foreach ($post['rm_roles'] as $id) {
                $role = Table\UserRoles::findById((int)$id);
                if (isset($role->id)) {
                    $role->delete();
                }
            }
        }
    }

    /**
     * Determine if list of user roles have pages
     *
     * @param  int $limit
     * @return boolean
     */
    public function hasPages($limit)
    {
        return (Table\UserRoles::findAll()->count() > $limit);
    }

    /**
     * Get count of user roles
     *
     * @return int
     */
    public function getCount()
    {
        return Table\UserRoles::findAll()->count();
    }

    /**
     * Get permissions from $_POST data
     *
     * @param  array $post
     * @return mixed
     */
    protected function getPermissions(array $post)
    {
        $permissions = [
            'allow' => [],
            'deny'  => []
        ];

        // Get new ones
        foreach ($post as $key => $value) {
            if (strpos($key, 'resource_') !== false) {
                $id         = substr($key, 9);
                $permission = $post['permission_' . $id];
                if (($value != '----') && ($permission != '----')) {
                    if ((bool)$permission) {
                        $permissions['allow'][] = [
                            'resource'   => $value,
                            'permission' => ((!empty($post['action_' . $id]) &&
                                ($post['action_' . $id] != '----')) ?
                                $post['action_' . $id] : null),
                        ];
                    } else {
                        $permissions['deny'][] = [
                            'resource'   => $value,
                            'permission' => ((!empty($post['action_' . $id]) &&
                                ($post['action_' . $id] != '----')) ?
                                $post['action_' . $id] : null),
                        ];
                    }
                }
            }
        }

        return $permissions;
    }

}