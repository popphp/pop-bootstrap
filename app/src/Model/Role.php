<?php

namespace App\Model;

use App\Table;

class Role extends AbstractModel
{

    public function getAll($limit = null, $page = null, $sort = null)
    {
        $order   = $this->getSortOrder($sort, $page);
        $options = ['order'  => $order];

        if (null !== $limit) {
            $page = ((null !== $page) && ((int)$page > 1)) ?
                ($page * $limit) - $limit : null;

            $options['offset'] = $page;
            $options['limit']  = $limit;
        }

        return Table\Roles::findAll($options, Table\Roles::ROW_AS_OBJECT)->rows();
    }

    public function getById($id)
    {
        $role = Table\Roles::findById((int)$id);
        if (isset($role->id)) {
            $data = $role->getColumns();
            $data['role_parent_id'] = $data['parent_id'];
            $this->data = array_merge($this->data, $data);
        }
    }

    public function save(array $post)
    {
        $role = new Table\Roles([
            'parent_id'         => ($post['role_parent_id'] != '----') ? (int)$post['role_parent_id'] : null,
            'name'              => html_entity_decode($post['name'], ENT_QUOTES, 'UTF-8'),
            'permissions'       => serialize($this->getPermissions($post))
        ]);
        $role->save();

        $this->data = array_merge($this->data, $role->getColumns());
    }

    public function update(array $post, $sess = null)
    {
        $role = Table\Roles::findById((int)$post['id']);
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

    public function remove(array $post)
    {
        if (isset($post['rm_roles'])) {
            foreach ($post['rm_roles'] as $id) {
                $role = Table\Roles::findById((int)$id);
                if (isset($role->id)) {
                    $role->delete();
                }
            }
        }
    }

    public function hasPages($limit)
    {
        return (Table\Roles::findAll(null, Table\Roles::ROW_AS_ARRAY)->count() > $limit);
    }

    public function getCount()
    {
        return Table\Roles::findAll(null, Table\Roles::ROW_AS_ARRAY)->count();
    }

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