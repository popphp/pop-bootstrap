<?php

namespace App\Controller\Roles;

use App\Controller\AbstractController;
use App\Form;
use App\Model;
use Pop\Paginator\Paginator;

class IndexController extends AbstractController
{

    /**
     * Index action method
     *
     * @return void
     */
    public function index()
    {
        $role = new Model\Role();

        if ($role->hasPages($this->application->config()['pagination'])) {
            $limit = $this->application->config()['pagination'];
            $pages = new Paginator($role->getCount(), $limit);
            $pages->useInput(true);
        } else {
            $limit = null;
            $pages = null;
        }

        $this->prepareView('roles/index.phtml');
        $this->view->title       = 'Roles';
        $this->view->pages       = $pages;
        $this->view->queryString = $this->getQueryString('sort');
        $this->view->roles       = $role->getAll($limit, $this->request->getQuery('page'), $this->request->getQuery('sort'));
        $this->send();
    }

    /**
     * Add action method
     *
     * @return void
     */
    public function add()
    {
        /*
        $this->prepareView('phire/roles/add.phtml');
        $this->view->title = 'Roles : Add';
        $role = new Model\Role();

        $fields = $this->application->config()['forms']['Phire\Form\Role'];
        $config = $this->application->config();

        $resources = ['----' => '----'];
        $parents   = ['----' => '----'];
        $roles     = (new Model\Role())->getAll();
        if (count($roles) > 0) {
            foreach ($roles as $r) {
                $parents[$r['id']] = $r['name'];
            }
        }

        foreach ($config['resources'] as $resource => $perms) {
            if (strpos($resource, '|') !== false) {
                $resource = explode('|', $resource);
                $resources[$resource[0]] = $resource[1];
            } else {
                $resources[$resource] = $resource;
            }
        }

        $fields[0]['role_parent_id']['value']  = $parents;
        $fields[2]['resource_1']['value'] = $resources;

        $this->view->form = new Form\Role($fields);

        if ($this->request->isPost()) {
            $this->view->form->addFilter('strip_tags')
                ->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
                ->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $role->save($this->request->getPost());
                $this->view->id = $role->id;
                $this->sess->setRequestValue('saved', true);
                $this->redirect(BASE_PATH . APP_URI . '/roles/edit/' . $role->id);
            }
        }

        $this->send();
        */
    }

    /**
     * Edit action method
     *
     * @param  int $id
     * @return void
     */
    public function edit($id)
    {
        /*
        $role = new Model\Role();
        $role->getById($id);

        if (!isset($role->id)) {
            $this->redirect(BASE_PATH . APP_URI . '/roles');
        }

        $this->prepareView('phire/roles/edit.phtml');
        $this->view->title     = 'Roles';
        $this->view->role_name = $role->name;

        $fields = $this->application->config()['forms']['Phire\Form\Role'];
        $config = $this->application->config();

        $resources = ['----' => '----'];
        $parents   = ['----' => '----'];
        $roles     = (new Model\Role())->getAll();
        if (count($roles) > 0) {
            foreach ($roles as $r) {
                if ($r['id'] != $id) {
                    $parents[$r['id']] = $r['name'];
                }
            }
        }

        foreach ($config['resources'] as $resource => $perms) {
            if (strpos($resource, '|') !== false) {
                $resource = explode('|', $resource);
                $resources[$resource[0]] = $resource[1];
            } else {
                $resources[$resource] = $resource;
            }
        }

        $fields[0]['role_parent_id']['value']  = $parents;
        $fields[2]['resource_1']['value'] = $resources;

        $this->view->form = new Form\Role($fields);
        $this->view->form->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8'])
            ->setFieldValues($role->toArray());

        if ($this->request->isPost()) {
            $this->view->form->addFilter('strip_tags')
                ->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $role = new Model\Role();
                $role->update($this->request->getPost(), $this->sess);
                $this->view->id = $role->id;
                $this->sess->setRequestValue('saved', true);
                $this->redirect(BASE_PATH . APP_URI . '/roles/edit/' . $role->id);
            }
        }

        $this->send();
        */
    }

    /**
     * Remove action method
     *
     * @return void
     */
    public function remove()
    {
        if ($this->request->isPost()) {
            $role = new Model\Role();
            $role->remove($this->request->getPost());
        }
        $this->sess->setRequestValue('removed', true);
        $this->redirect('/roles');
    }

}
