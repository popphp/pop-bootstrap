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
namespace App\Http\Web\Controller;

use App\Auth\Model\AuthUser;
use App\Http\Web\Form;

/**
 * Web users controller class
 *
 * @category   App
 * @package    App
 * @link       https://github.com/popphp/pop-bootstrap
 * @author     Nick Sagona, III <nick@nolainteractive.com>
 * @copyright  Copyright (c) 2012-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @version    4.1.0
 */
class UsersController extends AbstractController
{

    /**
     * Users index action method
     *
     * @return void
     */
    public function index()
    {
        $users = new AuthUser();
        $sess  = $this->application->services['session'];

        $this->prepareView('users/index.phtml');
        $this->view->title    = 'Users';
        $this->view->username = $sess->user->username;
        $this->view->userId   = $sess->user->id;
        $this->view->users    = $users->getAll();
        $this->send();
    }

    /**
     * Users add action method
     *
     * @return void
     */
    public function add()
    {
        $sess = $this->application->services['session'];

        $this->prepareView('users/add.phtml');
        $this->view->title    = 'Users : Add';
        $this->view->username = $sess->user->username;
        $this->view->form     = Form\User::createFromFieldsetConfig($this->application->config()['forms']['App\Web\Form\User']);
        $this->view->form->addColumn(1, 'form-left-column');
        $this->view->form->addColumn([2, 3], 'form-right-column');

        if ($this->request->isPost()) {
            $this->view->form->addFilter('strip_tags')
                 ->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8', false])
                 ->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                     ->addFilter('html_entity_decode', [ENT_QUOTES, 'UTF-8'])
                     ->filterValues();

                $user = new AuthUser();
                $user->save($this->view->form);

                $this->redirect('/users/' . $user->id);
            }
        }

        $this->send();
    }

    /**
     * Users edit action method
     *
     * @param  int $id
     * @return void
     */
    public function edit($id)
    {
        $user = (new AuthUser())->getById($id);

        if (!isset($user->id)) {
            $this->redirect('/users');
        }

        $sess = $this->application->services['session'];

        $this->prepareView('users/edit.phtml');
        $this->view->title    = 'Users : ' . $user->username;
        $this->view->username = $sess->user->username;
        $this->view->form     = Form\User::createFromFieldsetConfig($this->application->config()['forms']['App\Web\Form\User']);
        $this->view->form->addColumn(1, 'form-left-column');
        $this->view->form->addColumn([2, 3], 'form-right-column');
        $this->view->form->setFieldValues($user->toArray());

        if ($this->request->isPost()) {
            $this->view->form->addFilter('strip_tags')
                 ->addFilter('htmlentities', [ENT_QUOTES, 'UTF-8', false])
                 ->setFieldValues($this->request->getPost());

            if ($this->view->form->isValid()) {
                $this->view->form->clearFilters()
                     ->addFilter('html_entity_decode', [ENT_QUOTES, 'UTF-8'])
                     ->filterValues();

                $user = new AuthUser();
                $user->update($id, $this->view->form);

                $this->redirect('/users/' . $id);
            }
        }

        $this->send();
    }

    /**
     * Users remove action method
     *
     * @return void
     */
    public function remove()
    {
        if (null !== $this->request->getPost('rm_users')) {
            $user = new AuthUser();
            $user->remove($this->request->getPost('rm_users'));
        }
        $this->redirect('/users');
    }

}